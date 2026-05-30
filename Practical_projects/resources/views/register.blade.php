<html class="h-full light" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr" lang="en">
<head>
    <base href="../../">
    <title>IAMS - Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/vendors/keenicons/styles.bundle.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body class="antialiased flex items-center justify-center min-h-screen text-base text-foreground bg-background">

<div class="kt-card w-full max-w-2xl mx-auto">
    <form action="{{ route('register.post') }}" class="kt-card-content flex flex-col gap-5 p-10" id="register_form" method="POST">
        @csrf
        
        <div class="text-center mb-2.5">
            <h3 class="text-lg font-medium text-mono leading-none mb-2.5">
                Create an Account
            </h3>
        </div>
        
        <div class="flex flex-col gap-1">
            <label class="kt-form-label font-normal text-mono">Full Name</label>
            <input class="kt-input" placeholder="Your Name" type="text" name="name" value="{{ old('name') }}">
            @error('name')
                <span class="text-destructive text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="flex flex-col gap-1">
            <label class="kt-form-label font-normal text-mono">Email</label>
            <input class="kt-input" placeholder="email@email.com" type="text" name="email" value="{{ old('email') }}">
            @error('email')
                <span class="text-destructive text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="flex flex-col gap-1">
            <label class="kt-form-label font-normal text-mono">Password</label>
            <div class="kt-input" data-kt-toggle-password="true">
                <input name="password" placeholder="Enter Password" type="password" id="password">
                <button class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5" type="button" id="togglePassword">
                    <i class="ki-filled ki-eye text-muted-foreground"></i>
                </button>
            </div>
            @error('password')
                <span class="text-destructive text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col gap-1">
            <label class="kt-form-label font-normal text-mono">Confirm Password</label>
            <div class="kt-input">
                <input name="password_confirmation" placeholder="Confirm Password" type="password">
            </div>
        </div>
        
        <button class="kt-btn bg-prime text-white flex justify-center grow mt-2" type="submit">
            Register
        </button>

        <div class="text-center mt-2">
            <span class="text-sm text-muted-foreground">Already have an account?</span>
            <a href="{{ route('login') }}" class="text-sm text-prime font-medium hover:underline ms-1">Login here</a>
        </div>
    </form>
</div>

<script>
    // سكربت إظهار وإخفاء كلمة المرور
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const toggleIcon = togglePasswordBtn.querySelector('i');

    togglePasswordBtn.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        toggleIcon.classList.toggle('ki-eye');
        toggleIcon.classList.toggle('ki-eye-slash');
    });

    // إخفاء الأخطاء عند الكتابة
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('register_form');
        form.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', () => {
                const errorSpan = input.parentElement.parentElement.querySelector('.text-destructive') || input.parentElement.querySelector('.text-destructive');
                if (errorSpan) errorSpan.remove();
            });
        });
    });
</script>
</body>
</html>