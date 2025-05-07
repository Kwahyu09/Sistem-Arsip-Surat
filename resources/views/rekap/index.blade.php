<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold mb-4">Rekap Surat Masuk & Keluar per Tahun</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <canvas id="rekapChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = @json($years);
        const incoming = @json($incomingData);
        const outgoing = @json($outgoingData);

        const ctx = document.getElementById('rekapChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Surat Masuk',
                        backgroundColor: '#60A5FA',
                        data: incoming,
                    },
                    {
                        label: 'Surat Keluar',
                        backgroundColor: '#34D399',
                        data: outgoing,
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Surat'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tahun'
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
