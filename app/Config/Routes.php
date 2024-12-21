<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'UserController::index');

$routes->post('/login', 'UserController::login');
$routes->post('/logout', 'UserController::logout');

$routes->group('', ['filter' => 'auth'], function ($routes) {

    $routes->group('', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('/admin/dashboard', 'DashboardController::index');
        $routes->get('/admin/dokumen', 'DokumenController::index');
        $routes->get('/admin/dokumen/create', 'DokumenController::create');
        $routes->post('/admin/dokumen/store', 'DokumenController::store');
        $routes->post('/dokumen/markAsRead/(:num)', 'DokumenController::markAsRead/$1');
        $routes->get('/admin/dokumen/generate/(:num)', 'DokumenController::generate/$1');
        $routes->get('/admin/dokumen/generateSPD/(:num)', 'DokumenController::generateSPD/$1');
        $routes->get('/admin/dokumen/generate-word/(:num)', 'DokumenController::generateDocx/$1');
        $routes->get('/admin/dokumen/detailRBPD/(:num)', 'DokumenController::detailRBPD/$1');
        $routes->get('/admin/dokumen/createRBPD/(:num)/(:num)', 'DokumenController::createRBPD/$1/$2');
        $routes->post('/admin/dokumen/generateRBPD/(:num)/(:num)', 'DokumenController::generateRBPD/$1/$2');
        $routes->post('/admin/dokumen/storeRBPD', 'DokumenController::storeRBPD');
        $routes->get('/admin/dokumen/archive', 'DokumenController::arsip_index');
        $routes->post('/admin/dokumen/unarchive', 'DokumenController::unarchive');
        $routes->post('/admin/dokumen/bulkArsip', 'DokumenController::bulkArsip');
        $routes->post('/admin/dokumen/delete', 'DokumenController::delete');

        $routes->get('/admin/listuser', 'UserController::listuser');
        $routes->get('/user/create', 'UserController::create');
        $routes->post('/user/store', 'UserController::store');
        $routes->post('/user/delete', 'UserController::delete');
        $routes->get('/user/edit/(:segment)', 'UserController::edit/$1');
        $routes->post('/user/update/(:segment)', 'UserController::update/$1');
    });

    // Rute untuk UserController
    $routes->group('', ['filter' => 'role:pegawai'], function ($routes) {
        $routes->get('/dashboard', 'DashboardController::user');
        $routes->get('/dokumen', 'DokumenController::index');
        $routes->get('/dokumen/generate/(:num)', 'DokumenController::generate/$1');
        $routes->get('/dokumen/generateSPD/(:num)', 'DokumenController::generateSPD/$1');
        $routes->get('/dokumen/create', 'DokumenController::create');
        $routes->post('/dokumen/store', 'DokumenController::store');
        $routes->get('/dokumen/detailRBPD/(:num)', 'DokumenController::detailRBPD/$1');
        $routes->get('/dokumen/createRBPD/(:num)/(:num)', 'DokumenController::createRBPD/$1/$2');
        $routes->post('/dokumen/generateRBPD/(:num)/(:num)', 'DokumenController::generateRBPD/$1/$2');
        $routes->post('/dokumen/storeRBPD', 'DokumenController::storeRBPD');
        $routes->get('/dokumen/archive', 'DokumenController::arsip_index');
        $routes->post('/dokumen/delete', 'DokumenController::delete');
        $routes->post('/dokumen/bulkArsip', 'DokumenController::bulkArsip');
        $routes->post('/dokumen/unarchive', 'DokumenController::unarchive');
    });
});
