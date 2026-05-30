<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // 👈 تأكدي من وجود هذا السطر لاستخدامه في تحويل الطلب

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // تعريف الـ Middleware الخاص بالصلاحيات
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckUserRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        
        // 🛡️ الحل العالمي: الإمساك بخطأ الحجم الزائد ومنع شاشة الانهيار الحمراء
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            return redirect()->back()
                ->withErrors(['attachment' => 'The uploaded file is too large for the server. Please upload a smaller file (Max: 10MB).'])
                ->withInput();
        });

    })->create();