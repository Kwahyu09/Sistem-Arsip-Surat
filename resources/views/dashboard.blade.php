<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

{{-- Greeting --}}
<div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-6 rounded-xl shadow-lg text-white">
    <div class="flex items-center space-x-4">
        <div class="text-4xl">
        </div>
        <div>
            <h2 class="text-2xl font-extrabold mb-1">Halo, {{ Auth::user()->name }}!</h2>
            <p class="text-sm text-indigo-100">Selamat datang di Sistem Pengelolaan Surat.</p>
        </div>
    </div>
</div>

            {{-- Statistic Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <div class="bg-gradient-to-br from-blue-100 to-blue-200 p-6 rounded-xl shadow hover:shadow-lg transition flex items-center space-x-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-9 13h0a2 2 0 01-2-2v-5H9a2 2 0 01-2-2v0a2 2 0 012-2h1v-1a2 2 0 012-2h0a2 2 0 012 2v1h1a2 2 0 012 2v0a2 2 0 01-2 2h-1v5a2 2 0 01-2 2z" />
        </svg>
        <div>
            <div class="text-3xl font-bold text-blue-800">{{ $incomingCount }}</div>
            <div class="mt-1 text-sm text-blue-900">Surat Masuk</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-100 to-green-200 p-6 rounded-xl shadow hover:shadow-lg transition flex items-center space-x-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16M4 12h16M12 4v16" />
        </svg>
        <div>
            <div class="text-3xl font-bold text-green-800">{{ $outgoingCount }}</div>
            <div class="mt-1 text-sm text-green-900">Surat Keluar</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 p-6 rounded-xl shadow hover:shadow-lg transition flex items-center space-x-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-yellow-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
        </svg>
        <div>
            <div class="text-3xl font-bold text-yellow-800">{{ $archiveCount }}</div>
            <div class="mt-1 text-sm text-yellow-900">Total Arsip</div>
        </div>
    </div>
            </div>

            {{-- Grafik --}}
            <div class="bg-white shadow rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">📊 Grafik Jumlah Surat</h3>
                <canvas id="lettersChart" height="100"></canvas>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('lettersChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Surat Masuk', 'Surat Keluar', 'Arsip'],
                datasets: [{
                    label: 'Jumlah Surat',
                    data: [{{ $incomingCount }}, {{ $outgoingCount }}, {{ $archiveCount }}],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.6)',
                        'rgba(16, 185, 129, 0.6)',
                        'rgba(251, 191, 36, 0.6)'
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(251, 191, 36, 1)'
                    ],
                    borderWidth: 2,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
