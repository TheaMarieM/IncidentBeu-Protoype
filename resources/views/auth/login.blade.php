<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Welcome Back</h2>
        <p class="text-xs text-slate-500 mt-1">Please sign in to your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-regular fa-envelope text-slate-400 text-sm"></i>
                </div>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="your.email@spup.edu.ph"
                    class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600 text-sm bg-slate-50 placeholder-slate-400" />
            </div>
            @error('email')
                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-slate-400 text-sm"></i>
                </div>
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600 text-sm bg-slate-50 placeholder-slate-400" />
            </div>
            @error('password')
                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Checkbox & Link -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="flex items-center cursor-pointer select-none">
                <input id="remember_me" type="checkbox" name="remember" class="h-3.5 w-3.5 rounded border-slate-300 text-green-700 focus:ring-green-600 cursor-pointer">
                <span class="ml-2 text-xs text-slate-600 font-medium">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs text-green-700 hover:text-green-800 font-semibold hover:underline">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-green-800 hover:bg-green-900 text-white font-bold py-3 px-4 rounded-lg shadow-sm transition-colors duration-200 text-xs uppercase tracking-widest mt-2 flex items-center justify-center gap-2">
            Sign In <i class="fa-solid fa-arrow-right"></i>
        </button>
        
        <!-- Instructions Link -->
        <div class="text-center mt-6">
            <a href="{{ route('login.info') }}" class="text-xs text-slate-500 hover:text-green-700 font-medium transition-colors">
                New user? <span class="underline decoration-slate-300 hover:decoration-green-700">Read access instructions</span>
            </a>
        </div>
    </form>
</x-guest-layout>
