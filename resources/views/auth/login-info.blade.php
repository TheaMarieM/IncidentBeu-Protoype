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
                <p class="font-semibold border-b border-purple-200 mb-2 pb-1">Class Advisers (Password: password123)</p>
                <div class="space-y-3 text-xs">
                    <div>
                        <p class="font-medium">Mr. Juan Dela Cruz (Grade 7 - St. Matthew)</p>
                        <p class="text-purple-700">juan.delacruz@spup.edu.ph</p>
                    </div>
                    <div>
                        <p class="font-medium">Ms. Maria Clara (Grade 8 - St. Mark)</p>
                        <p class="text-purple-700">maria.clara@spup.edu.ph</p>
                    </div>
                    <div>
                        <p class="font-medium">Mr. Jose Rizal (Grade 9 - St. Luke)</p>
                        <p class="text-purple-700">jose.rizal@spup.edu.ph</p>
                    </div>
                    <div>
                        <p class="font-medium">Ms. Gabriela Silang (Grade 10 - St. John)</p>
                        <p class="text-purple-700">gabriela.silang@spup.edu.ph</p>
                    </div>
                    <div>
                        <p class="font-medium">Mr. Andres Bonifacio (Grade 11 - St. Paul)</p>
                        <p class="text-purple-700">andres.bonifacio@spup.edu.ph</p>
                    </div>
                </div>
            </div>

            <div class="bg-pink-50 p-3 rounded">
                <p class="font-semibold border-b border-pink-200 mb-2 pb-1">Parents (Password: password123)</p>
                <div class="space-y-3 text-xs">
                    <div>
                        <p class="font-medium">Roberto Dela Cruz (Child: Juan Dela Cruz - Gr 7 St. Matthew)</p>
                        <p class="text-pink-700">roberto.delacruz@spup.edu.ph</p>
                    </div>
                    <div>
                        <p class="font-medium">Elena Santos (Child: Maria Santos - Gr 8 St. Mark)</p>
                        <p class="text-pink-700">elena.santos@spup.edu.ph</p>
                    </div>
                    <div>
                        <p class="font-medium">Patricia Reyes (Child: Jose Reyes - Gr 9 St. Luke)</p>
                        <p class="text-pink-700">patricia.reyes@spup.edu.ph</p>
                    </div>
                    <div>
                        <p class="font-medium">Fernando Cruz (Child: Anna Cruz - Gr 10 St. John)</p>
                        <p class="text-pink-700">fernando.cruz@spup.edu.ph</p>
                    </div>
                    <div>
                        <p class="font-medium">Carmen Lopez (Child: Carlos Lopez - Gr 11 St. Paul)</p>
                        <p class="text-pink-700">carmen.lopez@spup.edu.ph</p>
                    </div>
                </div>
            </div>
            <div class="bg-teal-50 p-3 rounded">
                <p class="font-semibold border-b border-teal-200 mb-2 pb-1">Students (Password: student2026)</p>
                <div class="space-y-3 text-xs">
                    <div>
                        <p class="font-medium">Juan Dela Cruz (Grade 7 - St. Matthew)</p>
                        <p class="text-teal-700">ID: 2025-00124 | juan.delacruz.student@spup.edu.ph</p>
                    </div>
                    <div>
                        <p class="font-medium">Maria Santos (Grade 8 - St. Mark)</p>
                        <p class="text-teal-700">ID: 2025-00125 | maria.santos.student@spup.edu.ph</p>
                    </div>
                    <div>
                        <p class="font-medium">Jose Reyes (Grade 9 - St. Luke)</p>
                        <p class="text-teal-700">ID: 2025-00126 | jose.reyes.student@spup.edu.ph</p>
                    </div>
                    <div>
                        <p class="font-medium">Anna Cruz (Grade 10 - St. John)</p>
                        <p class="text-teal-700">ID: 2025-00127 | anna.cruz.student@spup.edu.ph</p>
                    </div>
                    <div>
                        <p class="font-medium">Carlos Lopez (Grade 11 - St. Paul)</p>
                        <p class="text-teal-700">ID: 2025-00128 | carlos.lopez.student@spup.edu.ph</p>
                    </div>
                </div>
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
