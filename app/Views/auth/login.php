<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="<?= base_url('css/output.css') ?>" rel="stylesheet">
    <style>
        /* Animasi gradasi latar belakang */
        @keyframes gradientBackground {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(45deg, #ff9a9e, #fad0c4, #fbc2eb, #a18cd1, #667eea);
            background-size: 300% 300%;
            animation: gradientBackground 8s ease infinite;
        }

        .card {
            animation: fadeInUp 0.8s ease-out;
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .no-select {
            user-select: none;
        }
    </style>
</head>

<body>
    <div class="w-full max-w-md p-8 card">
        <div class="flex justify-center mb-6">
            <img src="https://1.bp.blogspot.com/-YCWJVRc-y0M/Wg0U4GYmw2I/AAAAAAAAE80/Q0V4U7CE3_IsfFkLBRLlatIxMj_cmknXgCLcBGAs/s1600/Badan%2BPOM.png"
                alt="Badan POM Logo"
                class="h-20 w-auto">
        </div>

        <!-- Flash Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 p-3 text-green-700 bg-green-100 rounded-lg">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-3 text-red-700 bg-red-100 rounded-lg">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('login') ?>">
            <?= csrf_field() ?>

            <!-- NIP -->
            <div class="mb-5">
                <label for="identifier" class="block font-medium text-sm text-gray-700">NIP</label>
                <input id="identifier"
                    class="p-3 block mt-2 w-full h-12 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50"
                    type="text"
                    name="identifier"
                    value="<?= old('identifier', '') ?>"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Masukkan NIP">
                <?php if (isset($errors['identifier'])): ?>
                    <div class="mt-2 text-red-600 text-sm"><?= $errors['identifier'] ?></div>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                <input id="password"
                    class="p-3 block mt-2 w-full h-12 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan Password">
                <?php if (isset($errors['password'])): ?>
                    <div class="mt-2 text-red-600 text-sm"><?= $errors['password'] ?></div>
                <?php endif; ?>
            </div>

            <!-- Captcha -->
            <div class="mb-5">
                <label for="captcha" class="block font-medium text-sm text-gray-700">Captcha</label>
                <div class="mt-2 text-lg font-bold text-center bg-green-600 text-white rounded-lg p-3">
                    <?= session()->get('captcha') ?>
                </div>
                <input id="captcha"
                    class="p-3 block mt-2 w-full h-12 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50"
                    type="text"
                    name="captcha"
                    required
                    placeholder="Masukkan Captcha">
                <?php if (session()->getFlashdata('error') && session()->getFlashdata('error') === 'Captcha is incorrect.'): ?>
                    <div class="mt-2 text-red-600 text-sm"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center mb-4">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 shadow-sm">
                <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
            </div>

            <!-- Forgot Password and Login Button -->
            <div class="flex items-center justify-between">
                <a href="<?= base_url('password/request') ?>"
                    class="text-sm text-indigo-600 hover:underline focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Forgot your password?
                </a>
                <button type="submit"
                    class="bg-indigo-600 text-white rounded-lg px-4 py-2 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200">
                    Log in
                </button>
            </div>
        </form>
    </div>
</body>

</html>
