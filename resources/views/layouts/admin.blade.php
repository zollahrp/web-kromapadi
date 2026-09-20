<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kromapadi Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #FAFAFA; /* Soft white/gray background */
        }
        /* Custom scrollbar for a premium feel */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body class="text-gray-800 antialiased h-screen overflow-hidden flex">

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <!-- Topbar -->
        @include('layouts.topbar')

        <!-- Main Content (Scrollable) -->
        <main class="flex-1 overflow-y-auto bg-[#FAFAFA] p-6 lg:p-8 flex flex-col">
            <div class="max-w-[1400px] mx-auto w-full flex-1">
                @yield('content')
            </div>
            
            <!-- Footer -->
            <footer class="mt-auto pt-6 border-t border-gray-200 text-sm text-gray-400 flex justify-between items-center max-w-[1400px] mx-auto w-full">
                <div>&copy; {{ date('Y') }} Mulyaharja Admin Panel. All rights reserved.</div>
                <div>Versi 1.0.0</div>
            </footer>
        </main>
        
    </div>

    @stack('scripts')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.getElementById('success-alert');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    successAlert.style.opacity = '0';
                    successAlert.style.transform = 'translateY(-10px)';
                    
                    setTimeout(() => {
                        successAlert.style.display = 'none';
                    }, 500);
                }, 5000);
            }
        });
    </script>
</body>
</html>
