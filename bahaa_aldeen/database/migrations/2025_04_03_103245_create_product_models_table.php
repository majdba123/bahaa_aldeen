<?php

use App\Models\Inventory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_models', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Inventory::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name'); // اسم الموديل
            $table->string('code')->unique(); // كود الموديل (فريد)
            $table->decimal('price', 10, 2); // السعر مع خانات عشرية
            $table->string('size')->nullable(); // المقاس (يمكن أن يكون فارغاً)
            $table->string('color')->nullable(); // اللون (يمكن أن يكون فارغاً)
            $table->integer('quantity')->default(0); // الكمية (قيمة افتراضية 0)
            $table->enum('type', ['evening', 'wedding', 'engagement', 'party']); // النوع (سهرة/زفاف/خطوبة/حفلة)
            $table->enum('operation_type', ['rent', 'sale']); // نوع العملية (تأجير/بيع)
            $table->text('description')->nullable(); // الوصف (يمكن أن يكون فارغاً)
            $table->timestamps(); // created_at و updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_models');
    }
};
