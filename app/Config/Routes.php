<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');


// Rute yang hanya bisa diakses jika login
$routes->group('', ['filter' => 'auth'], function ($routes) {

    $routes->group('', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('/admin/dashboard', 'DashboardController::index');
        $routes->get('/admin/dokumen', 'DokumenController::index');
        $routes->get('/admin/dokumen/create', 'DokumenController::create');
        $routes->post('/dokumen/store', 'DokumenController::store');
        $routes->get('/admin/dokumen/generate/(:num)', 'DokumenController::generate/$1');
        $routes->get('/admin/dokumen/archive', 'DokumenController::arsip_index');
        $routes->post('/dokumen/bulkArsip', 'DokumenController::bulkArsip');
        $routes->post('/dokumen/delete', 'DokumenController::delete');

        //List user
        $routes->get('/admin/listuser', 'User::listuser');
        $routes->post('/admin/updateuser', 'User::updateuser'); 

    });

    // Rute untuk User
    $routes->group('', ['filter' => 'role:pegawai'], function ($routes) {
        $routes->get('/dashboard', 'DashboardController::user');
        $routes->get('/dokumen', 'DokumenController::index');
        $routes->get('/dokumen/create', 'DokumenController::create');
        // Tambahkan rute khusus user lainnya
    });
});


// Rute untuk User
$routes->get('/', 'User::index');
$routes->post('/login', 'User::login');
$routes->post('/logout', 'User::logout');
