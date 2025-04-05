<?php

namespace App\Http\Controllers;
use App\Http\Requests\inventory\StoreProductModelRequest; // استدعاء ملف الطلب
use App\Http\Requests\inventory\UpdateProductModelRequest; // استدعاء ملف الطلب
use Illuminate\Support\Facades\DB;
use App\Models\ModelImage;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\inventory\FilterProductsRequest;

use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Services\Inventory\ProductModelService;

class ProductModelController extends Controller
{

    protected $productService;

    public function __construct(ProductModelService $productService)
    {
        $this->productService = $productService;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductModelRequest $request): JsonResponse
    {
        // بدء transaction
        DB::beginTransaction();

        try {
            // إنشاء المنتج
            $product = $this->productService->createProduct($request->validated());

            $imageUrls = [];

            // معالجة الصور إذا وجدت
            if ($request->has('images') && is_array($request->images)) {
                foreach ($request->images as $imageFile) {
                    $imageName = Str::random(32) . '.' . $imageFile->getClientOriginalExtension();
                    $imagePath = 'products_images/' . $imageName;
                    $imageUrl = asset('storage/products_images/' . $imageName);

                    // تخزين الصورة
                    Storage::disk('public')->put($imagePath, file_get_contents($imageFile));

                    // إنشاء سجل الصورة
                    $imageRecord = ModelImage::create([
                        'product_model_id' => $product->id, // تغيير product_id إلى product_model_id
                        'path' => $imageUrl,
                    ]);

                    // في حالة فشل إنشاء سجل الصورة
                    if (!$imageRecord) {
                        throw new \Exception('Failed to create image record');
                    }

                    $imageUrls[] = $imageUrl;
                }
            }

            // تأكيد العملية إذا نجحت كل شيء
            DB::commit();

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product,
                'image_urls' => $imageUrls
            ], 201);

        } catch (\Exception $e) {
            // عمل Rollback في حالة الخطأ
            DB::rollBack();

            // حذف أي صور تم رفعها بالفعل
            if (!empty($imageUrls)) {
                foreach ($imageUrls as $url) {
                    $path = str_replace(asset('storage/'), '', $url);
                    Storage::disk('public')->delete($path);
                }
            }

            return response()->json([
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function update(UpdateProductModelRequest $request, $id): JsonResponse
    {

        $product = ProductModel::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $updatedProduct = $this->productService->updateProduct($request->validated(), $product);

        // التحقق مما إذا كان الطلب يحتوي على صور جديدة
        if ($request->has('images')) {
            // حذف الصور القديمة
            ModelImage::where('product_model_id', $product->id)->delete();
            $imageUrls = [];
            foreach ($request->images as $imageFile) {
                $imageName = Str::random(32) . '.' . $imageFile->getClientOriginalExtension();
                $imagePath = 'products_images/' . $imageName;
                $imageUrl = asset('storage/products_images/' . $imageName);
                // تخزين الصورة في التخزين
                Storage::disk('public')->put($imagePath, file_get_contents($imageFile));
                // إنشاء الصورة باستخدام الرابط الكامل
                ModelImage::create([
                    'product_id' => $product->id,
                    'path' => $imageUrl,
                ]);
                // إضافة رابط الصورة إلى الاستجابة
                $imageUrls[] = $imageUrl;
            }
        }

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $updatedProduct,
            'image_urls' => $imageUrls ?? []
        ], 200);
    }


    public function destroy($id): JsonResponse
    {
        $result = $this->productService->deleteProduct($id);
        return response()->json(['message' => $result['message']], $result['status']);
    }

    public function show($product_id)
    {
        $product = ProductModel::find($product_id);

        if (!$product) {
            return ['message' => 'Product not found', 'status' => 404];
        }
        $result = $this->productService->getProductById($product_id);

        return $result ;

    }

    public function latest_product(Request $request): JsonResponse
    {
        // عدد المنتجات في كل صفحة
        $perPage = $request->query('per_page', 5);
        // جلب أحدث المنتجات مع pagination
        $products = ProductModel::orderBy('created_at', 'desc')->paginate($perPage);
        // تخصيص استجابة الـ pagination مع البيانات المطلوبة
        $response = $products->toArray();
        return response()->json($response);
    }



    public function filterProducts(FilterProductsRequest $request): JsonResponse
    {
        try {
            // بدء بناء الاستعلام
            $query = ProductModel::with(['inventory', 'images']);

            // تطبيق جميع الفلاتر دفعة واحدة باستخدام when()
            $query->when($request->filled('inventory_ids'), function($q) use ($request) {
                $q->whereIn('inventory_id', $request->inventory_ids);
            })
            ->when($request->filled('branch_ids'), function($q) use ($request) {
                $q->whereHas('inventory', function($subQuery) use ($request) {
                    $subQuery->whereIn('branch_id', $request->branch_ids);
                });
            })
            ->when($request->filled('types'), function($q) use ($request) {
                $q->whereIn('type', $request->types);
            })
            ->when($request->filled('operation_types'), function($q) use ($request) {
                // تحويل القيمة إلى مصفوفة إذا كانت سلسلة
                $operationTypes = is_array($request->operation_types)
                    ? $request->operation_types
                    : explode(',', $request->operation_types);

                $q->whereIn('operation_type', $operationTypes);
            })
            ->when($request->filled('name'), function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->name.'%');
            })
            ->when($request->filled('description'), function($q) use ($request) {
                $q->where('description', 'like', '%'.$request->description.'%');
            })
            ->when($request->filled('code'), function($q) use ($request) {
                $q->where('code', 'like', '%'.$request->code.'%');
            })
            ->when($request->filled('min_price'), function($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            })
            ->when($request->filled('max_price'), function($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            })
            ->when($request->filled('min_quantity'), function($q) use ($request) {
                $q->where('quantity', '>=', $request->min_quantity);
            });

            // تطبيق الترتيب
            if ($request->filled('sort_by')) {
                $query->orderBy(
                    $request->sort_by === 'latest' ? 'created_at' : 'price',
                    str_contains($request->sort_by, 'desc') ? 'desc' : 'asc'
                );
            }

            // جلب النتائج مع التقسيم
            $products = $query->paginate($request->per_page ?? 15);

            return response()->json([
                'success' => true,
                'data' => $products,
                'message' => 'تم تصفية المنتجات بنجاح',
                'filters' => $request->validated()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التصفية',
                'error' => $e->getMessage()
            ], 500);
        }
    }



}
