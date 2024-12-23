<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $this->renderSection('title') ?></title>

    <link rel="shortcut icon" href="<?= base_url('assets/images/logo1-1.png') ?>">
    <link href="<?= base_url('css/output.css') ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>


</head>

<body class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100 h-screen">
    <div class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100 h-screen">
        <!-- Navbar -->
        <?= $this->include('pages/user/layout/navbar'); ?>

        <!-- Main Container -->
        <div class="-mt-px">
            <!-- Breadcrumb -->
            <div class="sticky top-0 inset-x-0 z-20 bg-white border-y px-4 sm:px-6 lg:px-8 lg:hidden dark:bg-neutral-800 dark:border-neutral-700">
                <div class="flex items-center py-2">
                    <!-- Navigation Toggle -->
                    <button type="button" class="size-8 flex justify-center items-center gap-x-2 border border-gray-200 text-gray-800 hover:text-gray-500 rounded-lg focus:outline-none focus:text-gray-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:text-neutral-200 dark:hover:text-neutral-500 dark:focus:text-neutral-500" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-application-sidebar" aria-label="Toggle navigation" data-hs-overlay="#hs-application-sidebar">
                        <span class="sr-only">Toggle Navigation</span>
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2" />
                            <path d="M15 3v18" />
                            <path d="m8 9 3 3-3 3" />
                        </svg>
                    </button>
                    <!-- End Navigation Toggle -->

                    <!-- Breadcrumb -->
                    <ol class="ms-3 flex items-center whitespace-nowrap">
                        <li class="flex items-center text-sm text-gray-800 dark:text-neutral-400">
                            Application Layout
                            <svg class="shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-neutral-500" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </li>
                        <li class="text-sm font-semibold text-gray-800 truncate dark:text-neutral-400" aria-current="page">
                            Dashboard
                        </li>
                    </ol>
                    <!-- End Breadcrumb -->
                </div>
            </div>
            <!-- End Breadcrumb -->
        </div>
        <?= $this->include('pages/user/layout/sidebar'); ?>

        <!-- Page Content -->
        <div class="w-full lg:ps-64">
            <?= $this->renderSection('content'); ?>
            <div id="toasts" class="absolute top-20 right-3"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

</body>

</html>