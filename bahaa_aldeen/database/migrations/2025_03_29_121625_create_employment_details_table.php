<?php

use App\Models\Employees;
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
        Schema::create('employment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employees::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->time('working_hours_from')->nullable(); // وقت العمل (من)
            $table->time('working_hours_to')->nullable(); // وقت العمل (إلى)
            $table->string('commission_type')->nullable(); // نوع العمولة
            $table->decimal('commission_value', 5, 2)->nullable(); // قيمة العمولة
            $table->decimal('salary', 10, 2)->nullable(); // الراتب
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_details');
    }
};
