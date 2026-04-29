<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'KFS — Scaffolding Sale & Rent System')</title>
    
    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('Backend/js/jquery-3.7.1.min.js') }}"></script>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('Backend/css/toastr.min.css') }}">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { 
                            50:'#eef2f7', 100:'#d4dbe6', 200:'#a9b7cd', 300:'#7e93b4', 
                            400:'#536f9b', 500:'#334155', 600:'#1e293b', 700:'#172033', 
                            800:'#0f172a', 900:'#0a101e' 
                        },
                        steel: { 
                            50:'#f8fafc', 100:'#f1f5f9', 200:'#e2e8f0', 300:'#cbd5e1', 
                            400:'#94a3b8', 500:'#64748b', 600:'#475569', 700:'#334155', 
                            800:'#1e293b', 900:'#0f172a' 
                        },
                        orange: { 
                            50:'#fff7ed', 100:'#ffedd5', 200:'#fed7aa', 300:'#fdba74', 
                            400:'#fb923c', 500:'#f97316', 600:'#ea580c', 700:'#c2410c', 
                            800:'#9a3412', 900:'#7c2d12' 
                        },
                        yellow: { 
                            50:'#fefce8', 100:'#fef9c3', 200:'#fef08a', 300:'#fde047', 
                            400:'#facc15', 500:'#eab308', 600:'#ca8a04', 700:'#a16207', 
                            800:'#854d0e', 900:'#713f12' 
                        },
                    },
                    fontFamily: {
                        display: ['Playfair Display', 'serif'],
                        body: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Custom Styles --}}
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #0f172a; }
        
        .blueprint-grid {
            background-image:
                linear-gradient(rgba(15,23,42,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15,23,42,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .blueprint-grid-dark {
            background-image:
                linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        
        /* Page system */
        .page { display: none; }
        .page.active { display: block; }
        
        /* Animations */
        .fade-up { opacity: 0; transform: translateY(30px); transition: opacity 0.7s ease-out, transform 0.7s ease-out; }
        .fade-up.visible { opacity: 1; transform: translateY(0); }
        .fade-left { opacity: 0; transform: translateX(-30px); transition: opacity 0.7s ease-out, transform 0.7s ease-out; }
        .fade-left.visible { opacity: 1; transform: translateX(0); }
        .fade-right { opacity: 0; transform: translateX(30px); transition: opacity 0.7s ease-out, transform 0.7s ease-out; }
        .fade-right.visible { opacity: 1; transform: translateX(0); }
        
        .card-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card-lift:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(15,23,42,0.12); }
        
        .hero-overlay { 
            background: linear-gradient(135deg, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0.6) 50%, rgba(15,23,42,0.75) 100%); 
        }
        
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
        
        /* Nav active */
        .nav-link { position: relative; }
        .nav-link::after { 
            content: ''; position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; 
            background: #f97316; transition: width 0.3s ease; 
        }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        .nav-link.active { color: #f97316 !important; }
        
        /* Mobile menu */
        .mobile-menu { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
        .mobile-menu.open { max-height: 600px; }
        
        /* Toast */
        .toast { transform: translateY(100px); opacity: 0; transition: all 0.4s ease; }
        .toast.show { transform: translateY(0); opacity: 1; }
        
        /* Pulse */
        @keyframes calcPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(249,115,22,0.3); }
            50% { box-shadow: 0 0 0 12px rgba(249,115,22,0); }
        }
        .calc-result-pulse { animation: calcPulse 1.5s ease-in-out 1; }
        
        /* Page transition */
        .page-transition { animation: pageIn 0.35s ease-out; }
        @keyframes pageIn { 
            from { opacity: 0; transform: translateY(16px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        
        /* Form styles */
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #0f172a;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 0.75rem;
            color: white;
            font-size: 0.875rem;
            transition: all 0.2s;
            outline: none;
        }
        .form-input:focus { 
            border-color: rgba(249,115,22,0.5); 
            box-shadow: 0 0 0 3px rgba(249,115,22,0.1); 
        }
        .form-input:disabled, .form-input[readonly] { 
            opacity: 0.6; cursor: not-allowed; background: #172033; 
        }
        .form-input::placeholder { color: #64748b; }
        .form-label { 
            display: block; font-size: 0.8125rem; font-weight: 500; 
            color: #94a3b8; margin-bottom: 0.375rem; 
        }
        .form-label .req { color: #f97316; }
        
        /* Transport toggle */
        .toggle-btn { transition: all 0.2s; }
        .toggle-btn.active { background: #f97316; color: white; border-color: #f97316; }
        
        /* Quotation item row */
        .quote-item-row { animation: slideIn 0.25s ease-out; }
        @keyframes slideIn { 
            from { opacity: 0; transform: translateX(-10px); } 
            to { opacity: 1; transform: translateX(0); } 
        }
        
        /* Auth form */
        .auth-input {
            width: 100%;
            padding: 0.875rem 1rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 0.75rem;
            color: white;
            font-size: 0.875rem;
            transition: all 0.2s;
            outline: none;
        }
        .auth-input:focus { 
            border-color: rgba(249,115,22,0.6); 
            box-shadow: 0 0 0 3px rgba(249,115,22,0.1); 
        }
        .auth-input::placeholder { color: #475569; }
    </style>
    
    @stack('styles')
</head>
<body class="blueprint-grid">

    {{-- Header/Navigation --}}
    @include('layouts.frontend.header')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.frontend.footer')

    {{-- Floating Buttons --}}
    @include('layouts.frontend.floating-buttons')

    {{-- Toast Notification --}}
    @include('layouts.frontend.toast')

    {{-- Core Scripts --}}
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // ===== APP STATE =====
        let currentUser = @json(session('user', null));
        let transportEnabled = false;
        let quoteItems = [];
        let quoteCounter = 1;
        let itemCounter = 1;
        
        const PRODUCTS = {
            'props':    { name: 'Adjustable Props',   rate: 8 },
            'pipes':    { name: 'Steel Pipes',         rate: 5 },
            'frames':   { name: 'Frame Systems',       rate: 15 },
            'planks':   { name: 'Steel Planks',        rate: 4 },
            'couplers': { name: 'Couplers',            rate: 2 },
            'wheels':   { name: 'Brake Wheels',        rate: 6 },
        };
        
        const DEPOSIT_AMOUNT = 500;
        
        // ===== ROUTING (SPA-like page switching) =====
        function navigateTo(page) {
            // Hide all pages
            document.querySelectorAll('.page').forEach(p => {
                p.classList.remove('active');
            });
            
            // Show target page
            const target = document.getElementById('page-' + page);
            if (target) {
                target.classList.add('active');
                target.classList.add('page-transition');
                setTimeout(() => target.classList.remove('page-transition'), 400);
            }
            
            // Update nav active state
            document.querySelectorAll('.nav-link[id^="nav-"]').forEach(link => link.classList.remove('active'));
            const navBtn = document.getElementById('nav-' + page);
            if (navBtn) navBtn.classList.add('active');
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            // Init page-specific logic
            if (page === 'quotation') initQuotationPage();
            if (page === 'projects') initProjectTabs();
            
            // Re-run animations
            setTimeout(runAnimations, 100);
            lucide.createIcons();
        }
        
        // ===== NAVBAR SCROLL =====
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 80) {
                navbar.classList.add('bg-navy-800/95', 'backdrop-blur-md', 'shadow-xl', 'border-b', 'border-white/5');
            } else {
                navbar.classList.remove('bg-navy-800/95', 'backdrop-blur-md', 'shadow-xl', 'border-b', 'border-white/5');
            }
        });
        
        // ===== MOBILE MENU =====
        let menuOpen = false;
        document.getElementById('mobileToggle')?.addEventListener('click', () => {
            menuOpen = !menuOpen;
            document.getElementById('mobileMenu').classList.toggle('open', menuOpen);
        });
        
        function closeMobileMenu() {
            menuOpen = false;
            document.getElementById('mobileMenu').classList.remove('open');
        }
        
        // ===== ANIMATIONS =====
        function runAnimations() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) entry.target.classList.add('visible');
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -50px 0px' });
            
            document.querySelectorAll('.page.active .fade-up, .page.active .fade-left, .page.active .fade-right').forEach(el => {
                el.classList.remove('visible');
                observer.observe(el);
            });
        }
        runAnimations();
        
        // ===== TOAST =====
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const msg = document.getElementById('toastMsg');
            msg.textContent = message;
            toast.className = toast.className.replace(/bg-\w+-\d+/, type === 'error' ? 'bg-red-500' : 'bg-green-500');
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 4000);
        }
    </script>
    <!-- Toastr JS -->
    <script src="{{ asset('Backend/js/toastr.min.js') }}"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "9000"
        };

        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if ($errors->any())
            toastr.error("{{ $errors->first() }}");
        @endif
    </script>
    @stack('scripts')
</body>
</html>