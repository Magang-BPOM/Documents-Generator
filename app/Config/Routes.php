<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/dashboard', 'DashboardController::index'); 

// Rute untuk Dokumen
$routes->get('/dokumen', 'DokumenController::index'); 
$routes->get('/dokumen/create', 'DokumenController::create'); 
$routes->post('/dokumen/store', 'DokumenController::store'); 
$routes->get('/dokumen/generate/(:num)', 'DokumenController::generate/$1'); 
$routes->get('dokumen/archive', 'DokumenController::arsip_index'); 
$routes->post('dokumen/bulkArsip', 'DokumenController::bulkArsip'); 
$routes->post('dokumen/delete', 'DokumenController::delete');

// Rute untuk User
$routes->get('/', 'User::index'); 
$routes->post('/login', 'User::login'); 
$routes->post('/logout', 'User::logout'); 