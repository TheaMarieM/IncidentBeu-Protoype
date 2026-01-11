<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        <h3 class="text-lg font-semibold mb-4">Default Login Credentials</h3>
        <div class="space-y-2">
            <div class="bg-blue-50 p-3 rounded">
                <p class="font-semibold">Discipline Chair</p>
                <p>ID: DC-001</p>
                <p>Email: discipline@spup.edu.ph</p>
                <p>Password: discipline2026</p>
            </div>
            <div class="bg-green-50 p-3 rounded">
                <p class="font-semibold">Principal</p>
                <p>ID: PR-001</p>
                <p>Email: principal@spup.edu.ph</p>
                <p>Password: principal2026</p>
            </div>
            <div class="bg-yellow-50 p-3 rounded">
                <p class="font-semibold">Assistant Principal</p>
                <p>ID: AP-001</p>
                <p>Email: assistant@spup.edu.ph</p>
                <p>Password: assistant2026</p>
            </div>
            <div class="bg-purple-50 p-3 rounded">
                <p class="font-semibold">Adviser</p>
                <p>ID: AD-001</p>
                <p>Email: adviser@spup.edu.ph</p>
                <p>Password: adviser2026</p>
            </div>
            <div class="bg-pink-50 p-3 rounded">
                <p class="font-semibold">Parent</p>
                <p>ID: PA-001</p>
                <p>Email: parent@spup.edu.ph</p>
                <p>Password: parent2026</p>
            </div>
            <div class="bg-teal-50 p-3 rounded">
                <p class="font-semibold">Student (Juan Dela Cruz)</p>
                <p>ID: 2025-00124</p>
                <p>Email: student@spup.edu.ph</p>
                <p>Password: student2026</p>
            </div>
        </div>
        <div class="mt-4 flex gap-3">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                        Logout First
                    </button>
                </form>
            @endauth
            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Go to Login
            </a>
        </div>
    </div>
</x-guest-layout>
