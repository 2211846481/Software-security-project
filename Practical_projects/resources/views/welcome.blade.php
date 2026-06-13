@extends('layout.BaseLayout')
@section('content')

<div class="kt-container-fluid py-8 flex flex-col items-center">
    <div class="flex flex-col items-center text-center mb-10">
        <div class="mb-6">
            <img alt="image" class="dark:hidden max-h-[140px]" src="assets/media/illustrations/21.svg">
            <img alt="image" class="hidden dark:block max-h-[140px]" src="assets/media/illustrations/21-dark.svg">
        </div>

        @auth
            <h1 class="text-2xl font-bold text-mono mb-3">
                Welcome back, {{ Auth::user()->name }}!
            </h1>
            <p class="text-sm text-secondary-foreground max-w-xl mb-4">
                You are currently logged in. Explore the system and manage your tasks securely.
            </p>
        @endauth

        @guest
            <h1 class="text-2xl font-bold text-mono mb-3">Welcome to SNH</h1>
            <p class="text-sm text-secondary-foreground max-w-xl mb-4">
                We're thrilled to have you on board! Explore our secure environment.
            </p>
        @endguest
    </div>
    <div class="w-full max-w-3xl bg-card border border-border rounded-xl p-6 mb-12 text-center shadow-xs">
        <h2 class="text-lg font-semibold text-mono mb-3">About The SNH Project</h2>
        <p class="text-sm text-secondary-foreground leading-relaxed">
            SNH is a secure web application built as part of the Secure Programming course.
            The system demonstrates real-world enterprise security practices including strict protection against
            XSS, SQL Injection, Cross-Site Request Forgery (CSRF), and Secure File Upload attacks.
        </p>
    </div>
    <div class="grid lg:grid-cols-2 gap-8 items-start w-full max-w-6xl">
        <div class="kt-card p-6 border border-border rounded-xl">
            <h3 class="text-base font-semibold text-mono mb-5">Leave a Comment</h3>

            @auth
                @if(session('success'))
                    <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm border border-green-200 flex items-center gap-2">
                        <i class="ki-filled ki-check-circle text-green-600"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-700 text-sm border border-red-200">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="flex items-center gap-3 mb-5 px-3 py-2.5 rounded-lg bg-muted/40 border border-border">
                    <div class="rounded-full size-9 bg-primary/20 flex items-center justify-center text-primary font-semibold text-sm shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-foreground">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-muted-foreground">Posting as yourself</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('comments.store') }}" enctype="multipart/form-data" class="flex flex-col gap-4">
                    @csrf
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Your Comment <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            name="comment_text"
                            id="comment_text"
                            rows="4"
                            maxlength="1000"
                            placeholder="Write your feedback about SNH..."
                            class="kt-input resize-none border border-border rounded-md p-2 text-sm"
                            required
                        >{{ old('comment_text') }}</textarea>
                        <div class="flex justify-end">
                            <span id="char-count" class="text-xs text-muted-foreground">0 / 1000</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Attach File
                            <span class="text-xs text-muted-foreground font-normal">(optional — image, PDF, or Word)</span>
                        </label>
                        <label for="attachment"
                               class="flex items-center gap-3 border border-dashed border-border rounded-md px-4 py-3 cursor-pointer bg-muted/20 hover:bg-muted/40 transition-colors group">
                            <i class="ki-filled ki-upload text-xl text-muted-foreground group-hover:text-primary transition-colors"></i>
                            <div class="flex flex-col">
                                <span id="file-label" class="text-sm text-muted-foreground">Click to upload a file</span>
                                <span class="text-xs text-muted-foreground">JPG, PNG, GIF, WEBP, PDF, DOC, DOCX</span>
                            </div>
                        </label>
                        <input
                            type="file"
                            id="attachment"
                            name="attachment"
                            accept="image/jpeg,image/png,image/gif,image/webp,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                            class="hidden"
                            onchange="updateFileLabel(this)"
                        >
                    <span id="file-error" class="text-destructive text-sm mt-1 hidden"></span>
                    </div>

                    <button type="submit" class="kt-btn bg-prime text-white justify-center py-2.5 rounded-md font-medium mt-1">
                        Submit Feedback
                    </button>
                </form>

            @else
                <div class="flex flex-col items-center gap-4 py-6 text-center">
                    <i class="ki-filled ki-lock text-4xl text-muted-foreground"></i>
                    <p class="text-sm text-secondary-foreground">
                        You need to be logged in to leave feedback or comments.
                    </p>
                    <div class="flex gap-3">
                        <a href="/login" class="kt-btn kt-btn-outline px-4 py-2">Log in</a>
                        <a href="/register" class="kt-btn bg-prime text-white px-4 py-2">Register</a>
                    </div>
                </div>
            @endauth
        </div>
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
                <div class="flex flex-col gap-4 max-h-[420px] overflow-y-auto pr-1">
                    @foreach($comments as $comment)
                        <div class="flex gap-3 pb-4 border-b border-border last:border-0">
                            <div class="rounded-full size-9 shrink-0 bg-primary/20 flex items-center justify-center text-primary font-semibold text-sm">
                                {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                            </div>

                            <div class="flex flex-col gap-1 flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-sm font-semibold text-foreground">
                                        {{ $comment->user->name }}
                                    </span>
                                    <span class="text-xs text-muted-foreground shrink-0">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-sm text-secondary-foreground break-words">
                                    {{ e($comment->comment_text) }}
                                </p>
                                @if($comment->file_path)
                                    @php
                                        $ext = strtolower(pathinfo($comment->file_path, PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        $isPdf   = $ext === 'pdf';
                                        $isWord  = in_array($ext, ['doc', 'docx']);
                                        $fileUrl = asset('storage/' . $comment->file_path);
                                        $fileName = basename($comment->file_path);
                                    @endphp

                                    @if($isImage)
                                        
                                        <img
                                            src="{{ $fileUrl }}"
                                            alt="Attached image"
                                            class="mt-2 max-h-40 rounded-md object-cover border border-border"
                                        >

                                    @elseif($isPdf)
                                        <a href="{{ $fileUrl }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="mt-2 flex items-center gap-2.5 px-3 py-2 rounded-md border border-border bg-red-50 dark:bg-red-950/20 hover:bg-red-100 dark:hover:bg-red-950/40 transition-colors no-underline group">
                                            <i class="ki-filled ki-file-pdf text-red-500 text-2xl shrink-0"></i>
                                            <div class="flex flex-col flex-1 min-w-0">
                                                <span class="text-xs font-medium text-foreground truncate">{{ $fileName }}</span>
                                                <span class="text-xs text-muted-foreground">PDF Document</span>
                                            </div>
                                            <i class="ki-filled ki-arrow-right text-muted-foreground text-sm group-hover:text-red-500 transition-colors shrink-0"></i>
                                        </a>

                                    @elseif($isWord)
                                        <a href="{{ $fileUrl }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="mt-2 flex items-center gap-2.5 px-3 py-2 rounded-md border border-border bg-blue-50 dark:bg-blue-950/20 hover:bg-blue-100 dark:hover:bg-blue-950/40 transition-colors no-underline group">
                                            <i class="ki-filled ki-file text-blue-500 text-2xl shrink-0"></i>
                                            <div class="flex flex-col flex-1 min-w-0">
                                                <span class="text-xs font-medium text-foreground truncate">{{ $fileName }}</span>
                                                <span class="text-xs text-muted-foreground">Word Document</span>
                                            </div>
                                            <i class="ki-filled ki-arrow-right text-muted-foreground text-sm group-hover:text-blue-500 transition-colors shrink-0"></i>
                                        </a>
                                        
                                    @else
                                        <a href="{{ $fileUrl }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="mt-2 flex items-center gap-2.5 px-3 py-2 rounded-md border border-border bg-muted/30 hover:bg-muted/50 transition-colors no-underline group">
                                            <i class="ki-filled ki-file text-muted-foreground text-2xl shrink-0"></i>
                                            <div class="flex flex-col flex-1 min-w-0">
                                                <span class="text-xs font-medium text-foreground truncate">{{ $fileName }}</span>
                                                <span class="text-xs text-muted-foreground">Attachment</span>
                                            </div>
                                            <i class="ki-filled ki-arrow-right text-muted-foreground text-sm group-hover:text-foreground transition-colors shrink-0"></i>
                                        </a>
                                    @endif
                                @endif
                                
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

{{-- ===== JavaScript ===== --}}
<script>
    
    const textarea = document.getElementById('comment_text');
    const charCount = document.getElementById('char-count');

    if (textarea && charCount) {
        const update = () => {
            const len = textarea.value.length;
            charCount.textContent = len + ' / 1000';
            charCount.style.color = len > 900
                ? 'var(--color-destructive, #ef4444)'
                : '';
        };
        textarea.addEventListener('input', update);
        update();
    }
    function updateFileLabel(input) {
    const label = document.getElementById('file-label');
    const errorSpan = document.getElementById('file-error');
    if (!label) return;
    
    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB
        
        if (file.size > maxSize) {
            errorSpan.textContent = 'File size must not exceed 2MB.';
            errorSpan.classList.remove('hidden');
            input.value = '';
            label.textContent = 'Click to upload a file';
            return;
        }
        
        errorSpan.classList.add('hidden');
        label.textContent = file.name;
    } else {
        label.textContent = 'Click to upload a file';
    }
}
</script>

@endsection
