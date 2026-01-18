<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SPUP-BEU | Adviser Portal</title>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            body { font-family: 'Inter', sans-serif; }
            .sidebar-item-active { background-color: rgba(255, 255, 255, 0.1); border-left: 4px solid #facc15; }
            .card-shadow { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-slate-900">
        @php
            $userName = auth()->user()->name ?? 'Adviser';
            $initials = strtoupper(\Illuminate\Support\Str::of($userName)->replaceMatches('/[^A-Za-z]/', '')->substr(0, 2));
            $isDashboard = request()->routeIs('adviser.dashboard');
            $isRegister = request()->routeIs('adviser.students.create');
        @endphp
        <aside class="fixed top-0 left-0 h-screen w-64 bg-green-900 text-white z-50 shadow-xl flex flex-col">
            <div class="p-6 border-b border-green-800">
                <h1 class="font-bold text-lg tracking-tight uppercase">SPUP-BEU</h1>
                <p class="text-[10px] text-yellow-400 font-bold uppercase tracking-widest mt-1">Adviser Portal</p>
            </div>

            <nav class="flex-1 py-6 space-y-1">
                <a href="{{ route('adviser.dashboard') }}" class="flex items-center gap-3 px-6 py-4 text-sm font-medium transition-all {{ $isDashboard ? 'sidebar-item-active' : 'text-green-100 hover:bg-green-800' }}">
                    <i class="fa-solid fa-chart-pie w-5 {{ $isDashboard ? 'text-yellow-400' : 'opacity-70' }}"></i> Dashboard
                </a>
                <a href="{{ route('adviser.students.create') }}" class="flex items-center gap-3 px-6 py-4 text-sm font-medium transition-all {{ $isRegister ? 'sidebar-item-active' : 'text-green-100 hover:bg-green-800' }}">
                    <i class="fa-solid fa-user-plus w-5 {{ $isRegister ? 'text-yellow-400' : 'opacity-70' }}"></i> Register Student
                </a>
            </nav>

            <div class="mt-auto border-t border-green-800">
                <form method="POST" action="{{ route('logout') }}" class="px-4 py-4">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-white hover:bg-red-600 hover:rounded-lg transition-all w-full text-left">
                        <i class="fa-solid fa-right-from-bracket w-5"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="ml-64 min-h-screen pb-24">
            @if (session('success'))
                <div class="mx-8 mt-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mx-8 mt-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded text-sm font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </body>
</html>
