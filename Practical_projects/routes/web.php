<?php


use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


// استبدلي مسار الـ '/' الحالي بهذا السطر ليقوم بجلب التعليقات من الدالة التي كتبناها في الـ Controller
Route::get('/', [AuthController::class, 'showAboutPage'])->name('home');

// Login page route
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/comments', [AuthController::class, 'storeComment'])->name('comments.store');
    
});