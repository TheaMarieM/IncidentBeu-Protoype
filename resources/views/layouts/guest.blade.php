<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SPUP-BEU') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            orange: {
                                50: '#fff7ed',
                                100: '#ffedd5',
                                200: '#fed7aa',
                                300: '#fdba74',
                                400: '#fb923c',
                                500: '#f97316',
                                600: '#ea580c',
                                700: '#c2410c',
                                800: '#9a3412',
                                900: '#7c2d12',
                            }
                        },
                        fontFamily: {
                            sans: ['Poppins', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
        
        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }
        </style>
    </head>
    <body class="bg-slate-50 min-h-screen font-sans antialiased text-slate-900">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-[380px]">
                <!-- Login Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
                    
                    <!-- Professional Header -->
                    <div class="bg-green-900 pt-10 pb-12 px-6 text-center relative overflow-hidden">
                        <!-- Subtle background accent -->
                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 mix-blend-soft-light"></div>
                        
                        <div class="relative z-10">
                            <!-- Logo Container -->
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-md mb-4 ring-4 ring-green-800 overflow-hidden">
                                <img src="{{ asset('images/spup_logo.png') }}" alt="SPUP Logo" class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Titles -->
                            <h1 class="text-2xl font-bold text-white tracking-tight mb-0.5">SPUP-BEU</h1>
                            <p class="text-[10px] font-bold text-yellow-400 uppercase tracking-widest border-t border-green-800 pt-2 mt-1 inline-block">Behavioral Incidents Management</p>
                        </div>
                    </div>

                    <!-- Form Content -->
                    <div class="px-6 py-8 bg-white">
                        {{ $slot }}
                    </div>
                </div>
                
                <!-- Footer -->
                <p class="text-center text-[10px] text-slate-400 mt-6 uppercase tracking-wider">
                    © {{ date('Y') }} St. Paul University Philippines
                </p>
            </div>
        </div>
    </body>
</html>
