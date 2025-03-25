<?php

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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade'); // العلاقة مع جدول الفروع
            $table->foreignId('job_id')->constrained('jobs')->onDelete('cascade'); // العلاقة مع جدول الوظائف
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // العلاقة مع جدول المستخدمين
            $table->string('name'); // اسم الموظف
            $table->string('national_id')->unique(); // رقم الهوية
            $table->string('nationality'); // الجنسية
            $table->enum('gender', ['male', 'female']); // الجنس
            $table->string('passport_number')->nullable(); // رقم جواز السفر
            $table->string('religion'); // الديانة
            $table->string('military_status'); // حالة التجنيد
            $table->string('insurance_number')->unique(); // الرقم التأميني
            $table->string('marital_status'); // الحالة الاجتماعية
            $table->date('birthday'); // تاريخ الميلاد
            $table->timestamps(); // حقول التوقيت
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
