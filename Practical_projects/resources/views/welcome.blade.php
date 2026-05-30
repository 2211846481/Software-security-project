@extends('layout.BaseLayout')
@section('content')

<div class="kt-container-fluid py-8 flex flex-col items-center">

    {{-- ===== الجزء العلوي: الصور والترحيب الذكي ===== --}}
    <div class="flex flex-col items-center text-center mb-10">
        <div class="mb-6">
            <img alt="image" class="dark:hidden max-h-[140px]" src="assets/media/illustrations/21.svg">
            <img alt="image" class="hidden dark:block max-h-[140px]" src="assets/media/illustrations/21-dark.svg">
        </div>
        
        @auth
            <h1 class="text-2xl font-bold text-mono mb-3">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-sm text-secondary-foreground max-w-xl mb-4">
                You are currently logged in. Explore the system and manage your tasks securely.
            </p>
        @endauth
        
        @guest
            <h1 class="text-2xl font-bold text-mono mb-3">Welcome to IAMS</h1>
            <p class="text-sm text-secondary-foreground max-w-xl mb-4">
                We're thrilled to have you on board! Explore our secure environment.
            </p>
        @endguest
    </div>

    {{-- ===== الجزء الأوسط: التعريف بالنظام والأهداف الأمنية ===== --}}
    <div class="w-full max-w-3xl bg-card border border-border rounded-xl p-6 mb-12 text-center shadow-xs">
        <h2 class="text-lg font-semibold text-mono mb-3">About The IAMS Project</h2>
        <p class="text-sm text-secondary-foreground leading-relaxed">
            IAMS is a secure web application built as part of the <strong>Secure Programming</strong> course.
            The system demonstrates real-world enterprise security practices including strict protection against
            <strong>XSS, SQL Injection, Cross-Site Request Forgery (CSRF)</strong>, and <strong>Secure File Upload attacks</strong>.
        </p>
    </div>

    {{-- ===== الجزء السفلي: نظام التعليقات الكامل والمحمي ===== --}}
    <div class="grid lg:grid-cols-2 gap-8 items-start w-full max-w-6xl">

        {{-- === فورم إضافة تعليق === --}}
        <div class="kt-card p-6 border border-border rounded-xl">
            <h3 class="text-base font-semibold text-mono mb-5">Leave a Comment</h3>

            @auth
                @if(session('success'))
                    <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-700 text-sm border border-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-700 text-sm border border-red-200">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('comments.store') }}" enctype="multipart/form-data" class="flex flex-col gap-4">
                    @csrf
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-foreground">Your Comment <span class="text-red-500">*</span></label>
                        <textarea name="comment_text" rows="4" maxlength="1000" placeholder="Write your feedback about IAMS..." class="kt-input resize-none border border-border rounded-md p-2" required>{{ old('comment_text') }}</textarea>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-foreground">Attach Screenshot <span class="text-xs text-muted-foreground">(optional)</span></label>
                        <input type="file" name="attachment" accept="image/jpeg,image/png,image/gif,image/webp" class="kt-input py-2 text-sm">
                    </div>

                    <button type="submit" class="kt-btn bg-prime text-white justify-center py-2.5 rounded-md font-medium">Submit Feedback</button>
                </form>
            @else
                <div class="flex flex-col items-center gap-4 py-6 text-center">
                    <i class="ki-filled ki-lock text-4xl text-muted-foreground"></i>
                    <p class="text-sm text-secondary-foreground">You need to be logged in to leave feedback or comments.</p>
                    <div class="flex gap-3">
                        <a href="/login" class="kt-btn kt-btn-outline px-4 py-2">Log in</a>
                        <a href="/register" class="kt-btn bg-prime text-white px-4 py-2">Register</a>
                    </div>
                </div>
            @endauth
        </div>

        {{-- === لوحة عرض التعليقات والآراء === --}}
        <div class="kt-card p-6 border border-border rounded-xl">
            <h3 class="text-base font-semibold text-mono mb-5">
                Community Feedback 
                <span class="text-xs text-muted-foreground font-normal">({{ $comments->count() ?? 0 }} total)</span>
            </h3>

            @if($comments->isEmpty())
                <div class="flex flex-col items-center py-10 text-center gap-3">
                    <i class="ki-filled ki-message-text text-4xl text-muted-foreground"></i>
                    <p class="text-sm text-secondary-foreground">No comments yet. Be the first to review the system!</p>
                </div>
            @else
                <div class="flex flex-col gap-4 max-h-[400px] overflow-y-auto pr-1">
                    @foreach($comments as $comment)
                        <div class="flex gap-3 pb-4 border-b border-border last:border-0">
                            <div class="rounded-full size-9 shrink-0 bg-primary/20 flex items-center justify-center text-primary font-semibold text-sm">
                                {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex flex-col gap-1 flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-sm font-semibold text-foreground">{{ $comment->user->name }}</span>
                                    <span class="text-xs text-muted-foreground shrink-0">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-secondary-foreground break-words">
                                    {{ e($comment->comment_text) }}
                                </p>
                                @if($comment->file_path)
                                    <img src="{{ asset('storage/' . $comment->file_path) }}" alt="attachment" class="mt-2 max-h-40 rounded-md object-cover border border-border">
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

@endsection