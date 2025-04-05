<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\EmployeeProfileController;
use App\Http\Controllers\ProductModelController;
use App\Http\Controllers\InventoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum','admin'])->group(function () {

    Route::get('/jobs', [JobsController::class, 'index']);


    Route::prefix('branches')->group(function () {
        Route::get('/get_all', [BranchesController::class, 'index']); // عرض جميع الفروع
        Route::get('/show/{id}', [BranchesController::class, 'show']); // عرض فرع محدد
        Route::post('/store', [BranchesController::class, 'store']); // إنشاء فرع جديد
        Route::put('update/{id}', [BranchesController::class, 'update']); // تحديث فرع
        Route::post('/filter', [BranchesController::class, 'filter']);
        Route::delete('delete/{id}', [BranchesController::class, 'destroy']); // حذف فرع
    });


    Route::prefix('employee')->group(function () {

        Route::post('/store', [EmployeesController::class, 'store']); // إنشاء فرع جديد
        Route::put('update/{id}', [EmployeesController::class, 'update']); // تحديث فرع
        Route::get('/get_all', [EmployeesController::class, 'index']);

        Route::get('/show/{id}', [EmployeesController::class, 'show']); // عرض فرع محدد
        Route::post('/update_status/{employee_id}', [EmployeesController::class, 'updateStatus']);
        //Route::post('/filter', [BranchesController::class, 'filter']);
    });


    Route::prefix('employee_profile')->group(function () {
        Route::post('/store', [EmployeeProfileController::class, 'store']);
        Route::put('/update/{employeeId}', [EmployeeProfileController::class, 'update']);
        Route::get('/show/{employeeId}', [EmployeeProfileController::class, 'show']);
        Route::delete('delete/{employeeId}', [EmployeeProfileController::class, 'destroy']);
    });

    Route::prefix('inventory')->group(function () {

        Route::prefix('product')->group(function () {
            Route::get('/get_latest', [ProductModelController::class, 'latest_product']);
            Route::post('/store', [ProductModelController::class, 'store']);
            Route::post('/update/{model_id}', [ProductModelController::class, 'update']);
            Route::delete('delete/{model_id}', [ProductModelController::class, 'destroy']);
            Route::get('/show/{model_id}', [ProductModelController::class, 'show']);
            Route::get('filter', [ProductModelController::class, 'filterProducts']);
        });

        Route::get('/get_all', [InventoryController::class, 'index']);

    });


});
