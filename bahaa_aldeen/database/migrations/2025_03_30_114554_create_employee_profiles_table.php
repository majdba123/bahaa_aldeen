<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Employees;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employees::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('college'); // الكلية
            $table->string('major'); // التخصص
            $table->string('university'); // الجامعة
            $table->year('graduation_year'); // سنة التخرج
            $table->string('governorate'); // المحافظة
            $table->string('city'); // المدينة
            $table->string('village')->nullable(); // القرية
            $table->string('landline_phone')->nullable(); // رقم الهاتف الأرضي
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_profiles');
    }
};
