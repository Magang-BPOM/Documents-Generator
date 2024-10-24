<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="<?= base_url('css/output.css') ?>" rel="stylesheet">
</head>
<body class="dark:bg-gray-900 bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-md rounded-lg p-8">
        <!-- Flash Message -->
        <?php if (session()->getFlashdata('status')): ?>
            <div class="mb-4 p-3 text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-200">
                <?= session()->getFlashdata('status') ?>
            </div>
        <?php endif; ?>

        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-gray-100 mb-6">Login</h2>

        <form method="POST" action="<?= base_url('login') ?>">
            <?= csrf_field() ?>

            <!-- NIP  -->
            <div class="mb-4">
                <label for="identifier" class="block font-medium text-sm text-gray-700 dark:text-gray-400">NIP</label>
                <input id="identifier" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" type="text" name="identifier" value="<?= old('identifier', '') ?>" required autofocus autocomplete="username">
                <?php if (isset($errors['identifier'])): ?>
                    <div class="mt-2 text-red-600 text-sm"><?= $errors['identifier'] ?></div>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-400">Password</label>
                <input id="password" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" type="password" name="password" required autocomplete="current-password">
                <?php if (isset($errors['password'])): ?>
                    <div class="mt-2 text-red-600 text-sm"><?= $errors['password'] ?></div>
                <?php endif; ?>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center mb-4">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <label for="remember_me" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</label>
            </div>

            <!-- Forgot Password and Login Button -->
            <div class="flex items-center justify-between">
                <?php if (isset($routes['password.request'])): ?>
                    <a class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline" href="<?= base_url('password/request') ?>">
                        Forgot your password?
                    </a>
                <?php endif; ?>

                <button type="submit" class="bg-indigo-600 text-white rounded-md px-4 py-2 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    Log in
                </button>
            </div>
        </form>
    </div>
</body>
</html>
