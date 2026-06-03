<html class="h-full light" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr" lang="en" data-kt-theme-swtich-initialized="true" data-kt-theme-switch-mode="light">
<head>
<base href="../../">
<title>
Metronic - Tailwind CSS
</title>
<meta charset="utf-8">
<meta content="follow, index" name="robots">
<link href="https://127.0.0.1:800/metronic-tailwind-html/demo5/index.html" rel="canonical">
<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
<meta content="" name="description">
<meta content="@keenthemes" name="twitter:site">
<meta content="@keenthemes" name="twitter:creator">
<meta content="summary_large_image" name="twitter:card">
<meta content="Metronic - Tailwind CSS " name="twitter:title">
<meta content="" name="twitter:description">
<meta content="assets/media/app/og-image.png" name="twitter:image">
<meta content="https://127.0.0.1:8001/metronic-tailwind-html/demo5/index.html" property="og:url">
<meta content="en_US" property="og:locale">
<meta content="website" property="og:type">
<meta content="@keenthemes" property="og:site_name">
<meta content="Metronic - Tailwind CSS " property="og:title">
<meta content="" property="og:description">
<meta content="assets/media/app/og-image.png" property="og:image">
<link href="assets/media/app/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180">
<link href="assets/media/app/favicon-32x32.png" rel="icon" sizes="32x32" type="image/png">
<link href="assets/media/app/favicon-16x16.png" rel="icon" sizes="16x16" type="image/png">
<link href="assets/media/app/favicon.ico" rel="shortcut icon">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<link href="assets/vendors/apexcharts/apexcharts.css" rel="stylesheet">
<link href="assets/vendors/keenicons/styles.bundle.css" rel="stylesheet">
<link href="assets/css/styles.css" rel="stylesheet">
</head>
<body class="antialiased flex items-center justify-center min-h-screen text-base text-foreground bg-background [--header-height:54px] [--sidebar-width:200px]" style="" data-kt-sticky-header="on" data-kt-sticky-sidebar="on">
<script>
const defaultThemeMode = 'light';
let themeMode;

if (document.documentElement) {
if (localStorage.getItem('kt-theme')) {
themeMode = localStorage.getItem('kt-theme');
} else if (
document.documentElement.hasAttribute('data-kt-theme-mode')
) {
themeMode =
document.documentElement.getAttribute('data-kt-theme-mode');
} else {
themeMode = defaultThemeMode;
}

if (themeMode === 'system') {
themeMode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

document.documentElement.classList.add(themeMode);
}
</script>
<div class="kt-card w-full max-w-2xl mx-auto">
<form action="{{ route('login.post') }}" class="kt-card-content flex flex-col gap-5 p-10 " id="sign_in_form" method="POST">
    @csrf
    
    @if ($errors->has('throttle'))
        <div id="throttle_alert" class="kt-alert kt-alert-danger text-destructive text-sm p-3 border border-destructive/20 bg-destructive/10 rounded-md mb-2">
            {{ $errors->first('throttle') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="kt-alert kt-alert-danger mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="text-center mb-2.5">
        <h3 class="text-lg font-medium text-mono leading-none mb-2.5">
            Login
        </h3>
    </div>
    
    <div class="flex flex-col gap-1">
        <label class="kt-form-label font-normal text-mono">
            Email
        </label>
        <input class="kt-input" placeholder="email@email.com" type="text" name="email" value="{{ old('email') }}">
        @error('email')
            <span class="text-destructive text-sm mt-1">{{ $message }}</span>
        @enderror
    </div>
    
    <div class="flex flex-col gap-1">
        <div class="flex items-center justify-between gap-1">
            <label class="kt-form-label font-normal text-mono">
                Password
            </label>
        </div>
        <div class="kt-input" data-kt-toggle-password="true" data-kt-toggle-password-initialized="true">
            <input name="password" placeholder="Enter Password" type="password">
            <button class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5" data-kt-toggle-password-trigger="true" type="button">
                <i class="ki-filled ki-eye text-muted-foreground"></i>
            </button>
        </div>
    </div>
    
    <button id="login_btn" class="kt-btn bg-prime text-white flex justify-center grow" type="submit">
        Login
    </button>

    <div class="text-center mt-2">
    <span class="text-sm text-muted-foreground">Don't have an account?</span>
    <a href="{{ route('register') }}" class="text-sm text-prime font-medium hover:underline ms-1">Register here</a>
    
</div>
</form>
</div>
</body>
<script>
    const passwordInput = document.querySelector('input[name="password"]');
    const togglePasswordBtn = document.querySelector('[data-kt-toggle-password-trigger]');
    const toggleIcon = togglePasswordBtn.querySelector('i');

    togglePasswordBtn.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        if (type === 'password') {
            toggleIcon.classList.remove('ki-eye-slash');
            toggleIcon.classList.add('ki-eye');
        } else {
            toggleIcon.classList.remove('ki-eye');
            toggleIcon.classList.add('ki-eye-slash');
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('sign_in_form');
    const loginBtn = document.getElementById('login_btn');
    const throttleAlert = document.getElementById('throttle_alert');

    if (throttleAlert) {
        loginBtn.style.display = 'none';
        setTimeout(() => {
            loginBtn.style.display = 'flex'; 
            throttleAlert.remove();       
        }, 60000); 
    }
    const hideSessionError = () => {
        const sessionError = form.querySelector('.kt-alert-danger:not(#throttle_alert)');
        if (sessionError) {
            sessionError.remove();
        }
    };

    const hideFieldErrors = () => {
        const errorSpans = form.querySelectorAll('.text-destructive');
        errorSpans.forEach(span => span.remove());
    };

    const formInputs = form.querySelectorAll('input');

    formInputs.forEach(input => {
        input.addEventListener('input', () => {
            hideSessionError();
            hideFieldErrors();
        });
    });
});
</script>
</html>