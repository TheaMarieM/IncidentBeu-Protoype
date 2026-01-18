<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Parent Portal') - SPUP-BEU</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .card-shadow {
            box-shadow: 0 1px 3px 0 rgba(0,0,0,0.08);
        }
        
        .sidebar-item-active {
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #facc15;
            padding-left: 12px;
        }
        
        .btn-primary {
            @apply px-4 py-2 bg-green-600 text-white rounded font-medium hover:bg-green-700 transition;
        }
        
        .btn-secondary {
            @apply px-4 py-2 bg-gray-200 text-gray-800 rounded font-medium hover:bg-gray-300 transition;
        }
        
        .status-pending {
            @apply bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold;
        }
        
        .status-resolved {
            @apply bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold;
        }
        
        .status-dismissed {
            @apply bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold;
        }
        
        .status-under-review {
            @apply bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold;
        }
    </style>
</head>
<body class="bg-gray-50 flex">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-green-900 min-h-screen flex flex-col fixed left-0 top-0 z-50">
        <div class="p-6 border-b border-green-800">
            <h1 class="text-white text-xl font-black tracking-tight">SPUP-BEU</h1>
            <p class="text-yellow-400 text-xs font-bold tracking-widest mt-0.5">PARENT PORTAL</p>
        </div>
        
        <nav class="flex-1 p-4">
            <a href="{{ route('parent.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 text-white text-sm font-semibold rounded transition-all mb-1 {{ request()->routeIs('parent.dashboard') ? 'sidebar-item-active' : 'hover:bg-green-800' }}">
                <i class="fa-solid fa-children w-4 text-center {{ request()->routeIs('parent.dashboard') ? 'text-yellow-400' : '' }}"></i>
                <span>My Children</span>
            </a>
            
            <a href="{{ route('parent.profile') }}" 
               class="flex items-center gap-3 px-4 py-3 text-white text-sm font-semibold rounded transition-all mb-1 {{ request()->routeIs('parent.profile') ? 'sidebar-item-active' : 'hover:bg-green-800' }}">
                <i class="fa-solid fa-user w-4 text-center {{ request()->routeIs('parent.profile') ? 'text-yellow-400' : '' }}"></i>
                <span>My Profile</span>
            </a>
        </nav>
        
        <div class="p-4 border-t border-green-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-3 text-white text-sm font-semibold rounded hover:bg-red-700 transition-all w-full">
                    <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="flex-1 ml-64">
        <!-- Top Bar -->
        <div class="bg-white border-b border-gray-200 px-8 py-4 card-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ isset($pageTitle) ? $pageTitle : 'My Children' }}
                    </h2>
                    <p class="text-gray-600 text-sm">Welcome back, {{ Auth::user()->first_name ?? 'Parent' }}</p>
                </div>
                <div class="flex items-center space-x-2 text-gray-600">
                    <i class="fas fa-calendar-day"></i>
                    <span>{{ now()->format('F d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="overflow-auto">
            <div class="p-8">
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-800 font-semibold mb-2">
                            <i class="fas fa-exclamation-circle"></i> There were some errors:
                        </p>
                        <ul class="text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-green-800 font-semibold">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </p>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>
    
</body>
</html>
