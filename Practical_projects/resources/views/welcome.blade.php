<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
     @extends('layout.BaseLayout')
       @section('content')
    
       <div class="kt-modal-body flex flex-col items-center pt-0 pb-10">
        <div class="mb-10">
         <img alt="image" class="dark:hidden max-h-[140px]" src="assets/media/illustrations/21.svg">
          <img alt="image" class="light:hidden max-h-[140px]" src="assets/media/illustrations/21-dark.svg">
        </div>
        
        {{-- عرض المحتوى بناءً على حالة المستخدم --}}
        
        {{-- إذا كان المستخدم مسجلاً دخوله --}}
        @auth
            <h3 class="text-lg font-medium text-mono text-center mb-3">
                 Welcome back, {{ Auth::user()->name }}!
            </h3>
            <div class="text-sm text-center text-secondary-foreground mb-7">
                You are currently logged in to your account.
                <br>
                Explore the system and manage your tasks.
            </div>
            
            
        @endauth
        
        {{-- إذا كان المستخدم غير مسجل دخوله (ضيف) --}}
        @guest
            <h3 class="text-lg font-medium text-mono text-center mb-3">
             Welcome to IAMS
            </h3>
            <div class="text-sm text-center text-secondary-foreground mb-7">
             We're thrilled to have you on board and excited for
             <br>
             the journey ahead together.
            </div>
            <div class="flex justify-center mb-2">
             
            </div>
        @endguest
        
       </div>
@endsection
</body>
</html>