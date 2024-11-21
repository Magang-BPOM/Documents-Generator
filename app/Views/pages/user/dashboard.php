<?= $this->extend('pages/user/layout/main'); ?>

<?= $this->section('title'); ?>
Dashboard
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb'); ?>
Application Layout
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb_current'); ?>
Dashboard
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="max-w-full mx-auto">
    <div class="bg-white dark:bg-neutral-900 shadow-lg rounded-xl p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 ">
            <!-- Total Surat -->
            <div class="bg-white text-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300  dark:bg-neutral-800 dark:border-neutral-700"">
    <div class=" flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-7ZM8 16a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Zm1-5a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H9Z" clip-rule="evenodd" />
                    </svg>

                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold dark:text-white">Total Surat</h2>
                    <p class="text-2xl font-bold dark:text-white"><?= $total_surat ?></p>
                </div>
            </div>
        </div>

        <!-- Surat Aktif (ikon checklist aktif) -->
        <div class="bg-white text-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300  dark:bg-neutral-800 dark:border-neutral-700"">
    <div class=" flex items-center">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Z" />
                    <path fill-rule="evenodd" d="M11 7V2h7a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Zm4.707 5.707a1 1 0 0 0-1.414-1.414L11 14.586l-1.293-1.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd" />
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold dark:text-white">Surat Aktif</h2>
                <p class="text-2xl font-bold dark:text-white"><?= $surat_aktif ?></p>
            </div>
        </div>
    </div>

    <!-- Surat Arsip (ikon arsip) -->
    <div class="bg-white text-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300  dark:bg-neutral-800 dark:border-neutral-700"">
    <div class=" flex items-center">
        <div class="flex-shrink-0">
            <svg class="w-8 h-8 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M4 4a2 2 0 1 0 0 4h16a2 2 0 1 0 0-4H4Zm0 6h16v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8Zm10.707 5.707a1 1 0 0 0-1.414-1.414l-.293.293V12a1 1 0 1 0-2 0v2.586l-.293-.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l2-2Z" clip-rule="evenodd" />
            </svg>

        </div>
        <div class="ml-4">
            <h2 class="text-lg font-semibold dark:text-white">Surat Arsip</h2>
            <p class="text-2xl font-bold dark:text-white"><?= $surat_arsip ?></p>
        </div>
    </div>
</div>

<!-- Surat Baru Bulan Ini (ikon dokumen baru) -->
<div class="bg-white text-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300  dark:bg-neutral-800 dark:border-neutral-700"">
    <div class=" flex items-center">
    <div class="flex-shrink-0">
        <svg class="w-8 h-8 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Zm2 0V2h7a2 2 0 0 1 2 2v6.41A7.5 7.5 0 1 0 10.5 22H6a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Z" clip-rule="evenodd" />
            <path fill-rule="evenodd" d="M9 16a6 6 0 1 1 12 0 6 6 0 0 1-12 0Zm6-3a1 1 0 0 1 1 1v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 1 1 0-2h1v-1a1 1 0 0 1 1-1Z" clip-rule="evenodd" />
        </svg>

        </svg>

    </div>
    <div class="ml-4">
        <h2 class="text-lg font-semibold dark:text-white">Surat Baru Bulan Ini</h2>
        <p class="text-2xl font-bold dark:text-white"><?= $surat_baru_bulan_ini ?></p>
    </div>
</div>
</div>
</div>

<!-- Grafik Surat Bulanan -->
<div class="mt-10 bg-white p-6 rounded-lg shadow-lg dark:bg-neutral-800 dark:border-neutral-700">
    <h2 class="text-xl font-semibold mb-6 text-gray-800">Grafik Surat Per Bulan</h2>
    <div style="height:420px; width: 100%;">
        <canvas id="suratChart"></canvas>
    </div>
</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('suratChart').getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(103, 58, 183, 0.8)');
    gradient.addColorStop(1, 'rgba(103, 58, 183, 0.2)');

    const suratChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($bulan) ?>,
            datasets: [{
                label: 'Jumlah Surat',
                data: <?= json_encode($jumlah_surat_per_bulan) ?>,
                backgroundColor: gradient,
                borderColor: 'rgba(103, 58, 183, 1)',
                borderWidth: 1,
                borderRadius: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1000,
                easing: 'easeInOutQuad'
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: {
                            size: 16
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 12
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#888',
                        font: {
                            size: 14
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(200, 200, 200, 0.3)'
                    },
                    ticks: {
                        color: '#888',
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    });
</script>

<?= $this->endSection(); ?>