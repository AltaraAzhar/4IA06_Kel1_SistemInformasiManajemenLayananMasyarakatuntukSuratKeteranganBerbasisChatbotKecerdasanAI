<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Layanan Surat Online - Kelurahan Pabuaran Mekar')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    
    <style>
        html {
            scroll-behavior: smooth;
        }

        .hero-overlay {
            background: linear-gradient(
                135deg,
                rgba(30, 41, 59, 0.85) 0%,
                rgba(15, 23, 42, 0.9) 100%
            );
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-white text-gray-900 font-sans">
    @include('components.navbar')
    
    <main>
        @yield('content')
    </main>
    
    @include('components.footer')
    
    <!-- Chatbot Widget -->
    @include('chatbot.widget')
    
    <!-- Mobile Menu Script -->
    <script>
        const btn = document.getElementById("mobile-menu-button");
        const menu = document.getElementById("mobile-menu");

        if (btn && menu) {
            btn.addEventListener("click", () => {
                menu.classList.toggle("hidden");
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>

