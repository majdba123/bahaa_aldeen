<?php

namespace App\Services\Inventory;

use App\Models\ProductModel;
use App\Models\ModelImage;
use Illuminate\Support\Facades\Storage;

class ProductModelService
{
    public function createProduct(array $data)
    {

        return ProductModel::create([
            'inventory_id' => $data['inventory_id'],
            'name' => $data['name'],
            'code' => $data['code'],
            'price' => $data['price'],
            'size' => $data['size'] ?? null,
            'color' => $data['color'] ?? null,
            'quantity' => $data['quantity'],
            'type' => $data['type'],
            'operation_type' => $data['operation_type'],
            'description' => $data['description'] ?? null,

        ]);
    }

    public function updateProduct(array $data, ProductModel $product): ProductModel
    {
        $product->update([
            'inventory_id' => $data['inventory_id'] ?? $product->inventory_id,
            'name' => $data['name'] ?? $product->name,
            'code' => $data['code'] ?? $product->code,
            'price' => $data['price'] ?? $product->price,
            'size' => $data['size'] ?? $product->size,
            'color' => $data['color'] ?? $product->color,
            'quantity' => $data['quantity'] ?? $product->quantity,
            'type' => $data['type'] ?? $product->type,
            'operation_type' => $data['operation_type'] ?? $product->operation_type,
            'description' => $data['description'] ?? $product->description,
        ]);

        return $product;
    }

    public function deleteProduct($id): array
    {
        $product = ProductModel::find($id);

        if (!$product) {
            return ['message' => 'Product not found', 'status' => 404];
        }
        // تنفيذ عملية الحذف باستخدام الـ "Soft Delete"
        $product->delete();
        return ['message' => 'Product deleted successfully', 'status' => 200];
    }


    public function getProductById($id)
    {
        $product = ProductModel::with('images')->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return $product;
    }


}
