<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment; 
use App\Models\User;    
use Illuminate\Support\Facades\Hash; 

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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
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
    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        return view('register');
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
       
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', 
        ]);

       
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Account created successfully! Welcome to SNH.');
    }

    /**
     * Display the combined Home/About page with comments.
     */
    public function showAboutPage()
    {

        $comments = Comment::with('user')->latest()->get();

        return view('welcome', compact('comments')); 
    }

    /**
     * Store a newly created comment in storage securely (Secure File Upload applied).
     */
    public function storeComment(Request $request)
    {
        $request->validate([
            'comment_text' => 'required|string|max:1000',
            'attachment'   => 'nullable|mimes:jpeg,png,jpg,gif,webp,pdf,doc,docx|max:10240', 
        ]);

        $comment = new Comment();
        $comment->comment_text = $request->comment_text;
        $comment->user_id = Auth::id();

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('comment_attachments', $fileName, 'public');
            $comment->file_path = $path;
        }

        $comment->save();

        return redirect()->back()->with('success', 'Thank you! Your comment has been posted.');
    }
}