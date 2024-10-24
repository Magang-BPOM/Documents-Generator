<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Dashboard::index');

$routes->get('/dok', 'DokumenController::index');
$routes->get('/dokumen/create', 'DokumenController::create');
$routes->post('/dokumen/submit', 'DokumenController::submit');
