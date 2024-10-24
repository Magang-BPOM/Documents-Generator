<head>
    <!-- Tambahkan di dalam tag <head> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<div id="hs-application-sidebar" class="hs-overlay [--auto-close:lg] hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform w-[260px] h-full hidden fixed inset-y-0 start-0 z-[60] bg-gray-50 border-e border-gray-200 lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 dark:bg-neutral-800 dark:border-neutral-700" role="dialog" tabindex="-1" aria-label="Sidebar">
    <div class="relative flex flex-col h-full max-h-full">

        <div class="px-6 pt-4 mb-4">
            <!-- Logo -->
            <a class="flex-none rounded-xl text-xl inline-block font-semibold focus:outline-none focus:opacity-80" href="#" aria-label="Preline">
                <img class="w-40 h-auto dark:hidden" src="https://1.bp.blogspot.com/-YCWJVRc-y0M/Wg0U4GYmw2I/AAAAAAAAE80/Q0V4U7CE3_IsfFkLBRLlatIxMj_cmknXgCLcBGAs/s1600/Badan%2BPOM.png" alt="Dummy Logo Black">
                <img class="w-40 h-auto hidden dark:block" src="https://1.bp.blogspot.com/-YCWJVRc-y0M/Wg0U4GYmw2I/AAAAAAAAE80/Q0V4U7CE3_IsfFkLBRLlatIxMj_cmknXgCLcBGAs/s1600/Badan%2BPOM.png" alt="Dummy Logo White">
            </a>
            <!-- End Logo -->
        </div>

        <div class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
    <ul class="space-y-2 px-4">
        <li>
            <a href="<?= base_url('/dashboard') ?>" class="flex items-center py-2 px-2.5 text-gray-800 rounded-lg dark:hover:bg-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white">
                <i class="fas fa-tachometer-alt text-lg" style="width: 20px; height: 20px;"></i> 
                <span class="ml-2">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="<?= base_url('/dok') ?>" class="flex items-center py-2 px-2.5 text-gray-800 rounded-lg dark:hover:bg-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white">
                <i class="fas fa-folder text-lg" style="width: 20px; height: 20px;"></i> 
                <span class="ml-2">Dokumen</span>
            </a>
        </li>
    </ul>
</div>


        <div class="px-4 py-2">
            <a href="<?= base_url('/logout') ?>" class="flex items-center py-2.5 px-4 text-red-600 rounded-lg hover:bg-red-100 focus:outline-none">
                <i class="fas fa-sign-out-alt"></i>
                <span class="ml-2">Logout</span>
            </a>
        </div>

    </div>
</div>
