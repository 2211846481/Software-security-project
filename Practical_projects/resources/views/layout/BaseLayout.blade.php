<!DOCTYPE html>
<html class="h-full light" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr" lang="en" data-kt-theme-swtich-initialized="true" data-kt-theme-switch-mode="light">
<head>
  <base href="../../">
  <title>Metronic - Tailwind CSS</title>
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
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" 
      rel="stylesheet"
      crossorigin="anonymous">

  <link href="assets/vendors/apexcharts/apexcharts.css" rel="stylesheet">
  <link href="assets/vendors/keenicons/styles.bundle.css" rel="stylesheet">
  <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body class="antialiased flex h-full text-base text-foreground bg-background [--header-height:54px] [--sidebar-width:200px]" data-kt-sticky-header="on" data-kt-sticky-sidebar="on">

  <!-- Theme Mode -->
  <script>
    const defaultThemeMode = 'light';
    let themeMode;
    if (document.documentElement) {
      if (localStorage.getItem('kt-theme')) {
        themeMode = localStorage.getItem('kt-theme');
      } else if (document.documentElement.hasAttribute('data-kt-theme-mode')) {
        themeMode = document.documentElement.getAttribute('data-kt-theme-mode');
      } else {
        themeMode = defaultThemeMode;
      }
      if (themeMode === 'system') {
        themeMode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      }
      document.documentElement.classList.add(themeMode);
    }
  </script>
  <!-- End of Theme Mode -->

  <!-- Main -->
  <div class="flex grow flex-col in-data-kt-[sticky-header=on]:pt-(--header-height)">

    <!-- Header -->
    <header class="flex items-center shrink-0 bg-background h-(--header-height) transition-[height] fixed z-10 top-0 left-0 right-0 shadow-xs backdrop-blur-md bg-white/70 dark:bg-background/70 border-b border-border active"
      data-kt-sticky="true"
      data-kt-sticky-class="transition-[height] fixed z-10 top-0 left-0 right-0 shadow-xs backdrop-blur-md bg-white/70 dark:bg-background/70 border-b border-border"
      data-kt-sticky-name="header"
      data-kt-sticky-offset="100px"
      id="header">
      <div class="kt-container-fluid flex flex-wrap justify-between items-center lg:gap-4" id="header_container">

        <div class="flex items-center gap-2 lg:gap-5">
          <a href="/">
            <img class="dark:hidden min-h-[34px]" src="assets/media/app/mini-logo-circle.svg">
            <img class="hidden dark:inline-block min-h-[34px]" src="assets/media/app/mini-logo-circle-dark.svg">
          </a>
          <div class="hidden lg:flex items-center">
            <div class="kt-menu kt-menu-default" data-kt-menu="true">
              <div class="kt-menu-item kt-menu-item-dropdown"
                data-kt-menu-item-offset="0,10px"
                data-kt-menu-item-placement="bottom-start"
                data-kt-menu-item-toggle="dropdown"
                data-kt-menu-item-trigger="hover">
                <button class="kt-menu-toggle text-mono text-sm font-medium">
                  SNH Team
                  <span class="kt-menu-arrow"></span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-3 ms-auto">
          <div class="flex items-center gap-1">
            <div data-kt-dropdown="true"
              data-kt-dropdown-offset="10px, 10px"
              data-kt-dropdown-offset-rtl="-10px, 10px"
              data-kt-dropdown-placement="bottom-end"
              data-kt-dropdown-placement-rtl="bottom-start">
            </div>
          </div>

          <div data-kt-dropdown="true"
            data-kt-dropdown-offset="10px, 10px"
            data-kt-dropdown-offset-rtl="-20px, 10px"
            data-kt-dropdown-placement="bottom-end"
            data-kt-dropdown-placement-rtl="bottom-start"
            data-kt-dropdown-trigger="click">

            <div class="cursor-pointer shrink-0" data-kt-dropdown-toggle="true">
              <i class="ki-filled ki-user text-2xl"></i>
              <i class="ki-filled ki-down"></i>
            </div>

            <div class="kt-dropdown-menu w-[250px]" data-kt-dropdown-menu="true">
              @auth
                <div class="flex items-center justify-between px-2.5 py-1.5 gap-1.5">
                  <div class="flex items-center gap-2">
                    <div class="rounded-full size-9 shrink-0 bg-primary/20 flex items-center justify-center text-primary font-semibold text-sm">
                      {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex flex-col gap-1.5">
                      <span class="text-sm text-foreground font-semibold leading-none">{{ Auth::user()->name }}</span>
                      <a class="text-xs text-secondary-foreground hover:text-primary font-medium leading-none">{{ Auth::user()->email }}</a>
                    </div>
                  </div>
                </div>

                <div class="px-2.5 pt-1.5 mb-2.5 flex flex-col gap-3.5">
                  <div class="flex items-center gap-2 justify-between">
                    <span class="flex items-center gap-2">
                      <i class="ki-filled ki-moon text-base text-muted-foreground"></i>
                      <span class="font-medium text-2sm">Dark Mode</span>
                    </span>
                    <input class="kt-switch" data-kt-theme-switch-state="dark" data-kt-theme-switch-toggle="true" name="check" type="checkbox" value="1">
                  </div>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="kt-btn kt-btn-outline justify-center w-full">Log out</button>
                  </form>
                </div>
              @endauth 

              @guest 
                <div class="flex items-center justify-between px-2.5 py-1.5 gap-1.5">
                  <div class="flex items-center gap-2">
                    <div class="rounded-full size-9 shrink-0 bg-secondary/20 flex items-center justify-center text-secondary font-semibold text-sm">
                      G
                    </div>
                    <div class="flex flex-col gap-1.5">
                      <span class="text-sm text-foreground font-semibold leading-none">Guest User</span>
                      <span class="text-xs text-secondary-foreground font-medium leading-none">Not logged in</span>
                    </div>
                  </div>
                </div>

                <div class="px-2.5 pt-1.5 mb-2.5 flex flex-col gap-3.5">
                  <div class="flex items-center gap-2 justify-between">
                    <span class="flex items-center gap-2">
                      <i class="ki-filled ki-moon text-base text-muted-foreground"></i>
                      <span class="font-medium text-2sm">Dark Mode</span>
                    </span>
                    <input class="kt-switch" data-kt-theme-switch-state="dark" data-kt-theme-switch-toggle="true" name="check" type="checkbox" value="1">
                  </div>

                  <a class="kt-btn kt-btn-outline justify-center w-full" href="/login">Log in</a>
                  <a class="kt-btn bg-prime text-white justify-center w-full mt-2" href="/register">Register</a>
                </div>
              @endguest 

            </div>
          </div>
        </div>

      </div>
    </header>
    <!-- End of Header -->

    <!-- Navbar -->
    <div class="bg-background border-y border-t border-border mb-5 lg:mb-10">
      <div class="kt-container-fluid flex flex-wrap justify-between items-center gap-2">
        <div class="grid">
          <div class="kt-scrollable-x-auto">
            <div class="kt-menu gap-5 lg:gap-7.5" data-kt-menu="true">

              <div class="kt-menu-item py-3.5 border-b border-transparent kt-menu-item-active:border-b-mono kt-menu-item-here:border-b-mono">
                <a class="kt-menu-link gap-2.5" href="/">
                  <span class="kt-menu-title text-nowrap font-medium text-sm text-secondary-foreground kt-menu-item-active:text-mono kt-menu-item-active:font-medium kt-menu-link-hover:text-mono">
                    Home/About SNH
                  </span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End of Navbar -->

    <!-- Content -->
    <main class="flex flex-col grow" id="content" role="content">

      @yield('content')

      <div class="grid gap-5 lg:gap-7.5">
        <div class="grid lg:grid-cols-3 gap-y-5 lg:gap-7.5 items-stretch">
          <div class="lg:col-span-2">
            <div class="grid grid-cols-1 gap-5 lg:gap-7.5 h-full items-stretch"></div>
          </div>
          <div class="lg:col-span-1">
            <div class="grid grid-cols-1 gap-5 lg:gap-7.5 h-full items-stretch"></div>
          </div>
        </div>

        <div class="grid lg:grid-cols-1 gap-5 lg:gap-7.5 items-stretch">
          <div class="grid">
            <div class="kt-card kt-card-grid h-full min-w-full">
              <div class="kt-card-table">
                <div class="grid datatable-initialized" data-kt-datatable="true" data-kt-datatable-page-size="5">
                  <div class="kt-scrollable-x-auto"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </main>
    <!-- End of Content -->

    {{-- ✅ تم نقل Footer خارج main --}}
    <footer class="footer">
      <div class="kt-container-fluid">
        <div class="flex flex-col md:flex-row justify-center md:justify-between items-center gap-3 py-5">
          <div class="flex order-2 md:order-1 gap-2 font-normal text-sm">
            <span class="text-muted-foreground">2026©</span>
            <a class="text-secondary-foreground hover:text-primary" href="/">Team SNH.</a>
          </div>
        </div>
      </div>
    </footer>
    <!-- End of Footer -->

  </div>
  <!-- End of Main -->

  <!-- Scripts -->
  <script src="assets/js/core.bundle.js"></script>
  <script src="assets/vendors/ktui/ktui.min.js"></script>
  <script src="assets/vendors/apexcharts/apexcharts.min.js"></script>
  <script src="assets/js/widgets/general.js"></script>
  <!-- End of Scripts -->

</body>
</html>