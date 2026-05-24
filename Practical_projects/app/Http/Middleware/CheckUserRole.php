<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // التحقق أولاً من أن المستخدم مسجل دخوله
        if (!Auth::check()) {
            return redirect('/login');
        }

        // جلب دور المستخدم الحالي
        $userRole = (string) Auth::user()->role;
        
        // التحقق مما إذا كان دور المستخدم موجودًا في قائمة الأدوار المسموح بها
        if (! in_array($userRole, $roles)) {
            // إذا لم يكن مصرحًا له، أرجع خطأ 403 (Unauthorized)
            abort(403, 'Unauthorized action.');
        }

        // إذا كان مصرحًا له، أكمل معالجة الطلب
        return $next($request);
    }
}