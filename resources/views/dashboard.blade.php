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
                    <div>
                        <h2 class="text-2xl font-extrabold mb-1">Halo, {{ Auth::user()->name }}!</h2>
                        <p class="text-sm text-indigo-100">Selamat datang di Sistem Pengelolaan Surat.</p>
                    </div>
                </div>
            </div>

            {{-- Statistic Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                {{-- Surat Masuk --}}
<div class="bg-gradient-to-br from-blue-100 to-blue-200 p-6 rounded-xl shadow hover:shadow-lg transition flex items-center space-x-4">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-700" viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 16.5l4-4h-3V3h-2v9.5H8l4 4z" />
        <path fill-rule="evenodd" d="M4 19a2 2 0 002 2h12a2 2 0 002-2v-6h-2v6H6v-6H4v6z" clip-rule="evenodd" />
    </svg>
    <div>
        <div class="text-3xl font-bold text-blue-800">{{ $incomingCount }}</div>
        <div class="mt-1 text-sm text-blue-900">Surat Masuk</div>
    </div>
</div>

{{-- Surat Keluar --}}
<div class="bg-gradient-to-br from-green-100 to-green-200 p-6 rounded-xl shadow hover:shadow-lg transition flex items-center space-x-4">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-700" viewBox="0 0 24 24" fill="currentColor">
        <path d="M10.5 6l6 6-6 6v-4.5H3v-3h7.5V6z" />
    </svg>
    <div>
        <div class="text-3xl font-bold text-green-800">{{ $outgoingCount }}</div>
        <div class="mt-1 text-sm text-green-900">Surat Keluar</div>
    </div>
</div>

{{-- Total Arsip --}}
<div class="bg-gradient-to-br from-yellow-100 to-yellow-200 p-6 rounded-xl shadow hover:shadow-lg transition flex items-center space-x-4">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-yellow-700" viewBox="0 0 24 24" fill="currentColor">
        <path d="M4 3a1 1 0 00-1 1v2a1 1 0 001 1h16a1 1 0 001-1V4a1 1 0 00-1-1H4zM3 9a1 1 0 011-1h16a1 1 0 011 1v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9zm9 3a1 1 0 00-1 1v3h2v-3a1 1 0 00-1-1z" />
    </svg>
    <div>
        <div class="text-3xl font-bold text-yellow-800">{{ $archiveCount }}</div>
        <div class="mt-1 text-sm text-yellow-900">Total Arsip</div>
    </div>
</div>
</div>

            {{-- Grafik Per Bulan --}}
            <div class="bg-white shadow rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">📈 Grafik Surat Masuk & Keluar per Bulan - {{ now()->year }}</h3>
                <canvas id="monthlyChart" height="100"></canvas>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Surat Masuk',
                        data: @json($incomingPerMonth),
                        backgroundColor: '#3b82f6'
                    },
                    {
                        label: 'Surat Keluar',
                        data: @json($outgoingPerMonth),
                        backgroundColor: '#10b981'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Statistik Surat Masuk & Keluar per Bulan - {{ now()->year }}'
                    },
                    legend: {
                        position: 'top'
                    }
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
