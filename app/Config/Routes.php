<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Dashboard::index');


$routes->post('store', 'DokumenController::store'); 
$routes->get('/dokumen/semua', 'DokumenController::index');
$routes->get('/dokumen/create', 'DokumenController::create');

$routes->get('surat/', 'DokumenController::surat');
$routes->get('surat/(:num)', 'DokumenController::generate/$1');

$routes->get('/', 'User::index');
$routes->post('login', 'User::login');
$routes->get('logout', 'User::logout');