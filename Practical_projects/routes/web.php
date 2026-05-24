<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AgreementController;
use App\Http\Controllers\AgreementTypeController;
use App\Http\Controllers\PartnerInstitutionController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;


// Public routes (unprotected)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Login page route
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');


// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // =========================================================================
    // AGREEMENTS ROUTES
    // =========================================================================
    // المسار الرئيسي لعرض جميع الاتفاقيات، تم تسميته hedaya
    Route::get('/hedaya', [AgreementController::class, 'index'])->name('hedaya');
    
    Route::prefix('agreements')->group(function () {
        // Route for the new agreement creation page
        Route::get('/create', [AgreementController::class, 'create'])->name('agreements.create');
        // Route to store a new agreement
        Route::post('/', [AgreementController::class, 'store'])->name('agreements.store');
        // Route for showing a specific agreement
        Route::get('/{agreement}', [AgreementController::class, 'show'])->name('agreements.show');
        // Route for editing a specific agreement
        Route::get('/{agreement}/edit', [AgreementController::class, 'edit'])->name('agreements.edit');
        // Route for updating a specific agreement
        Route::put('/{agreement}', [AgreementController::class, 'update'])->name('agreements.update');
        // Route for deleting a specific agreement
        Route::delete('/{agreement}', [AgreementController::class, 'destroy'])->name('agreements.destroy');
    });
    
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');

    // =========================================================================
    // PARTNER INSTITUTIONS ROUTES
    // =========================================================================
    // المسار القديم للتوافق مع الواجهة الحالية
    Route::get('/abrar', [PartnerInstitutionController::class, 'index'])->name('partners.list');
    
      // =========================================================================
    // PARTNER INSTITUTIONS ROUTES
    // =========================================================================
    
    Route::get('/partners', [PartnerInstitutionController::class, 'index'])->name('partners.index');
    
    // المسارات التي تتطلب صلاحيات (المسؤول ومستخدم القسم)
    Route::middleware('role:2,1')->group(function () {
        Route::get('/partners/create', [PartnerInstitutionController::class, 'create'])->name('partners.create');
        Route::post('/partners', [PartnerInstitutionController::class, 'store'])->name('partners.store');
        Route::get('/partners/{partner}/edit', [PartnerInstitutionController::class, 'edit'])->name('partners.edit');
        Route::put('/partners/{partner}', [PartnerInstitutionController::class, 'update'])->name('partners.update');
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
         Route::get('/documents', [DocumentController::class, 'store'])->name('documents.store');
    });

    // المسارات التي تتطلب صلاحيات (المسؤول فقط)
    Route::middleware('role:2')->group(function () {
        Route::delete('/partners/{partner}', [PartnerInstitutionController::class, 'destroy'])->name('partners.destroy');
    });

    // **ملاحظة: هذا المسار يجب أن يأتي في النهاية**
    Route::get('/partners/{partner}', [PartnerInstitutionController::class, 'show'])->name('partners.show');

    Route::get('/ansam', function () {
        return view('ANSAM');
    })->name('ansam');
    
    Route::get('/addstudent', function () {
        return view('addstudent');
    })->name('addstudent');
    
    // Student management routes
    Route::get('/salma', [DocumentController::class, 'index'])->name('show');


    // =========================================================================
    // User management routes (protected for Admin only)
    // =========================================================================
    Route::middleware('role:2')->group(function () {
        // Route::resource covers all operations (view, add, edit, delete)
        Route::resource('employees', UserController::class);
        
        // مسارات صريحة لتغطية المسارات التي كانت موجودة في ملفك الأصلي
        Route::get('/user', [UserController::class, 'index'])->name('employees.list');
        Route::get('/adduser', [UserController::class, 'create'])->name('adduser');
        
    
        
    });

    // Department management routes
    Route::get('/manal', [DepartmentController::class, 'index'])->name('departments.list');
    Route::resource('departments', DepartmentController::class);
    

    // =========================================================================
    // Department management routes that require "Admin" privileges
    // =========================================================================
    
        Route::get('/agreement_types', [AgreementTypeController::class, 'index'])->name('agreement_types.index');
    
    Route::middleware('role:2')->group(function () {
        Route::resource('agreement_types', AgreementTypeController::class)->except(['index']);
    });


    // =========================================================================
    // Documents Routes
    // =========================================================================
    Route::middleware(['auth', 'role:2,1'])->group(function () {
        // مسار عرض نموذج إضافة مستند جديد
        Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
        
        // مسار تخزين بيانات المستند الجديد
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        
        // مسار عرض نموذج تعديل مستند موجود
        Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        
        // مسار تحديث بيانات المستند في قاعدة البيانات
        Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
        
        // مسار حذف مستند معين
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    });

    // مسارات متاحة لجميع الأدوار (المسؤول, مستخدم القسم, العارض)
    // تشمل: عرض القائمة والتحميل
    Route::middleware(['auth', 'role:2,1,0'])->group(function () {
        // مسار عرض قائمة المستندات
        Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
        
        // مسار تحميل مستند معين
        Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    });

});