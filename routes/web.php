<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeDepartmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestFormController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/employee', [UserController::class, 'index'])->name('employee');
    Route::get('/employee-edit/{id}', [UserController::class, 'edit'])->name('employee-edit');
    Route::post('/employee-edit', [UserController::class, 'update'])->name('employee-update');
    Route::delete('/employee-delete/{id}', [UserController::class, 'destroy'])->name('employee-delete/{id}');
    
    Route::get('/employee-department', [EmployeeDepartmentController::class, 'index'])->name('employee-department');
    Route::get('/employee-by-department', [EmployeeDepartmentController::class, 'indexBydepartment'])->name('employee-by-department');
    Route::get('/employee-edit-department-ad', [EmployeeDepartmentController::class, 'permission'])->name('employee-edit-department-ad');
    Route::post('/employee-department-ad', [EmployeeDepartmentController::class, 'assignPermission'])->name('employee-department-ad');
    Route::get('/employee-edit-department', [EmployeeDepartmentController::class, 'department'])->name('employee-edit-department');
    Route::post('/employee-to-department', [EmployeeDepartmentController::class, 'store'])->name('employee-to-department');
    Route::delete('/employee-to-delete-department/{user_Id}/{department_Id}', [EmployeeDepartmentController::class, 'destroy'])->name('employee-delete-department');

    Route::get('/department', [DepartmentController::class, 'index'])->name('department');
    Route::get('/department-create', [DepartmentController::class, 'create'])->name('department-create');
    Route::get('/department-edit/{id}', [DepartmentController::class, 'edit'])->name('department-edit');
    Route::put('/department-update/{id}', [DepartmentController::class, 'update'])->name('department-update');
    Route::post('/department', [DepartmentController::class, 'store'])->name('department-store');
    Route::delete('/department/{id}', [DepartmentController::class, 'destroy'])->name('department-delete');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/request', [RequestFormController::class, 'show'])->name('request');
    Route::get('/request-staff-create', [RequestFormController::class, 'createForStaff'])->name('request-staff-create');
    Route::post('/request', [RequestFormController::class, 'store'])->name('request.store');

    Route::get('/request-list', [RequestFormController::class, 'index'])->name('request-list');
    Route::get('/request-manger-create', [RequestFormController::class, 'createForManger'])->name('request-manger-create');
    Route::post('/request-manger-approve', [RequestFormController::class, 'updateStatus'])->name('request-manger-approve');

});

require __DIR__ . '/auth.php';
