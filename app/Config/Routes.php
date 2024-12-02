<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'User::index');

$routes->post('/login', 'User::login');
$routes->post('/logout', 'User::logout');

$routes->group('', ['filter' => 'auth'], function ($routes) {

    $routes->group('', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('/admin/dashboard', 'DashboardController::index');
        $routes->get('/admin/dokumen', 'DokumenController::index');
        $routes->get('/admin/dokumen/create', 'DokumenController::create');
        $routes->post('/admin/dokumen/store', 'DokumenController::store');
        $routes->get('/admin/dokumen/generate/(:num)', 'DokumenController::generate/$1');
        $routes->get('/admin/dokumen/generateSPD/(:num)', 'DokumenController::generateSPD/$1');
        $routes->get('/admin/dokumen/generate-word/(:num)', 'DokumenController::generateWord/$1');
        $routes->get('/admin/dokumen/archive', 'DokumenController::arsip_index');
        $routes->post('/admin/dokumen/bulkArsip', 'DokumenController::bulkArsip');
        $routes->post('/admin/dokumen/delete', 'DokumenController::delete');

        $routes->get('/admin/listuser', 'User::listuser');
        $routes->get('/user/create', 'User::create');
        $routes->post('/user/store', 'User::store');
        $routes->post('/user/delete', 'User::delete');
        $routes->get('user/edit/(:segment)', 'UserController::edit/$1');
        $routes->post('user/update/(:segment)', 'UserController::update/$1');
    });

    // Rute untuk User
    $routes->group('', ['filter' => 'role:pegawai'], function ($routes) {
        $routes->get('/dashboard', 'DashboardController::user');
        $routes->get('/dokumen', 'DokumenController::index');
        $routes->get('/dokumen/generate/(:num)', 'DokumenController::generate/$1');
        $routes->get('/dokumen/generateSPD/(:num)', 'DokumenController::generateSPD/$1');
        $routes->get('/dokumen/create', 'DokumenController::create');
        $routes->post('/dokumen/store', 'DokumenController::store');
        $routes->get('/dokumen/archive', 'DokumenController::arsip_index');
        $routes->post('/dokumen/delete', 'DokumenController::delete');
        $routes->post('/dokumen/bulkArsip', 'DokumenController::bulkArsip');
        $routes->post('/dokumen/unarchive', 'DokumenController::unarchive');
    });
});
