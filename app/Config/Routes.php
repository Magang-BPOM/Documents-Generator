<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Dashboard::index');


$routes->post('store', 'DokumenController::store'); 
$routes->get('/surat/create', 'DokumenController::index');

$routes->get('/', 'User::index');
$routes->post('login', 'User::login');
$routes->get('logout', 'User::logout');