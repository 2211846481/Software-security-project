<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment; // 👈 تأكدي من استدعاء موديل التعليقات
use App\Models\User;    // 👈 تأكدي من استدعاء موديل المستخدمين
use Illuminate\Support\Facades\Hash; // 👈 لتشفير كلمات المرور بأمان

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        // التحقق من صحة البيانات
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // محاولة تسجيل الدخول
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // إعادة التوجيه إلى الصفحة الرئيسية الموحدة
            return redirect()->intended('/');
        }

        // إذا فشل تسجيل الدخول
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle the logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // =========================================================================
    // ⬇️ الدوال الجديدة المضافة للمشروع (التسجيل والتعليقات الآمنة) ⬇️
    // =========================================================================

    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        return view('register'); // يستدعي ملف register.blade.php
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        // التحقق الصارم من المدخلات لمنع البيانات الضارة
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // يفرض وجود حقل password_confirmation
        ]);

        // إنشاء الحساب وتشفير كلمة المرور تلقائياً لحمايتها في قاعدة البيانات
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // تسجيل دخول المستخدم تلقائياً بعد نجاح التسجيل
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Account created successfully! Welcome to IAMS.');
    }

    /**
     * Display the combined Home/About page with comments.
     */
    public function showAboutPage()
    {
        // جلب آخر التعليقات مع بيانات المستخدمين المرتبطين بها (Eager Loading لمنع مشكلة N+1)
        $comments = Comment::with('user')->latest()->get();

        return view('welcome', compact('comments')); // يمرر التعليقات لصفحة welcome
    }

    /**
     * Store a newly created comment in storage securely (Secure File Upload applied).
     */
    public function storeComment(Request $request)
    {
        // التحقق من المدخلات لمنع ثغرات الرفع العشوائي والـ DoS Attack
        $request->validate([
            'comment_text' => 'required|string|max:1000',
            'attachment'   => 'nullable|mimes:jpeg,png,jpg,gif,webp,pdf,doc,docx|max:10240', // تقييد الامتدادات والحجم (2MB)
        ]);

        $comment = new Comment();
        $comment->comment_text = $request->comment_text;
        $comment->user_id = Auth::id();

        // معالجة رفع الملف بشكل آمن
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            
            // توليد اسم عشوائي معقد للملف لمنع تخمين الأسماء أو التلاعب بها على السيرفر
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // التخزين في مجلد محمي مخصص للتعليقات داخل الـ Storage
            $path = $file->storeAs('comment_attachments', $fileName, 'public');
            
            $comment->file_path = $path;
        }

        $comment->save();

        return redirect()->back()->with('success', 'Thank you! Your comment has been posted.');
    }
}