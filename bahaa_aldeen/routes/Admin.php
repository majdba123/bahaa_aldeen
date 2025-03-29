<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\BranchesController;

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
});
