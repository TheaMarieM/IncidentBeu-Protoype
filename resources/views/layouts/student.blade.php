<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Portal') - SPUP-BEU</title>
    
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
    </style>
</head>
<body class="bg-gray-50 flex">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-green-900 min-h-screen flex flex-col fixed left-0 top-0 z-50">
        <div class="p-6 border-b border-green-800">
            <h1 class="text-white text-xl font-black tracking-tight">SPUP-BEU</h1>
            <p class="text-yellow-400 text-xs font-bold tracking-widest mt-0.5">STUDENT PORTAL</p>
        </div>
        
        <nav class="flex-1 p-4">
            <a href="{{ route('student.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 text-white text-sm font-semibold rounded transition-all mb-1 {{ request()->routeIs('student.dashboard') ? 'sidebar-item-active' : 'hover:bg-green-800' }}">
                <i class="fa-solid fa-calendar-check w-4 text-center {{ request()->routeIs('student.dashboard') ? 'text-yellow-400' : '' }}"></i>
                <span>My Attendance</span>
            </a>
            
            <a href="{{ route('student.profile') }}" 
               class="flex items-center gap-3 px-4 py-3 text-white text-sm font-semibold rounded transition-all mb-1 {{ request()->routeIs('student.profile') ? 'sidebar-item-active' : 'hover:bg-green-800' }}">
                <i class="fa-solid fa-user w-4 text-center {{ request()->routeIs('student.profile') ? 'text-yellow-400' : '' }}"></i>
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
        @yield('content')
    </main>
    
</body>
</html>
