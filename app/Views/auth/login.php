<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="<?= base_url('css/output.css') ?>" rel="stylesheet">
</head>

<body class="dark:bg-gray-900 bg-gray-100 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-md rounded-lg p-8">
        <div class="flex justify-center mb-6">
            <img src="https://1.bp.blogspot.com/-YCWJVRc-y0M/Wg0U4GYmw2I/AAAAAAAAE80/Q0V4U7CE3_IsfFkLBRLlatIxMj_cmknXgCLcBGAs/s1600/Badan%2BPOM.png"
                alt="Badan POM Logo"
                class="h-20 w-auto">
        </div>
        <!-- Flash Message -->
        <?php if (session()->getFlashdata('status')): ?>
            <div class="mb-4 p-3 text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-200">
                <?= session()->getFlashdata('status') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('login') ?>">
            <?= csrf_field() ?>

            <!-- NIP -->
            <div class="mb-5">
                <label for="identifier" class="block font-medium text-sm text-gray-700 dark:text-gray-300">NIP</label>
                <input id="identifier"
                    class="p-2 block mt-2 w-full h-12  border border-gray-400 bg-transparent dark:bg-transparent dark:text-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    type="text"
                    name="identifier"
                    value="<?= old('identifier', '') ?>"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Masukkan NIP"
                    >
                <?php if (isset($errors['identifier'])): ?>
                    <div class="mt-2 text-red-600 text-sm"><?= $errors['identifier'] ?></div>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Password</label>
                <input id="password"
                    class="p-2 block mt-2 w-full h-12  border border-gray-400 bg-transparent dark:text-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan Password"
                    >
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
                <a class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="<?= base_url('password/request') ?>">
                    Forgot your password?
                </a>

                <button type="submit" class="bg-indigo-600 text-white rounded-md px-4 py-2 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-150">
                    Log in
                </button>
            </div>
        </form>
    </div>
</body>

</html>