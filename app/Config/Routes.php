<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==========================================
// RUTE PUBLIK (Untuk Pemain & Ketua Pemain)
// *Tidak memerlukan login sesuai instruksi*
// ==========================================
$routes->get('/', 'Home::index');
$routes->get('player/(:num)', 'Home::player/$1');
$routes->get('leaderboard', 'Home::leaderboard');
$routes->get('compare', 'Home::compare');

// ==========================================
// RUTE AUTENTIKASI ADMIN
// ==========================================
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// ==========================================
// RUTE ADMIN (Dilindungi Filter 'adminAuth')
// ==========================================
$routes->group('admin', ['filter' => 'adminAuth'], static function ($routes) {
    // Dashboard
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('dashboard', 'Admin\DashboardController::index');

    // Manajemen Pemain
    $routes->get('players', 'Admin\PlayerController::index');
    $routes->get('players/(:num)/stats', 'Admin\PlayerController::stats/$1');
    $routes->get('players/create', 'Admin\PlayerController::create');
    $routes->post('players/store', 'Admin\PlayerController::store');
    $routes->get('players/edit/(:num)', 'Admin\PlayerController::edit/$1');
    $routes->post('players/update/(:num)', 'Admin\PlayerController::update/$1');
    $routes->get('players/delete/(:num)', 'Admin\PlayerController::delete/$1');

    // Manajemen Nilai Statistik
    $routes->get('stats', 'Admin\StatController::index');
    $routes->get('stats/create', 'Admin\StatController::create');
    $routes->get('stats/create/(:num)', 'Admin\StatController::create/$1');
    $routes->post('stats/store', 'Admin\StatController::store');
    $routes->get('stats/edit/(:num)', 'Admin\StatController::edit/$1');
    $routes->post('stats/update/(:num)', 'Admin\StatController::update/$1');
    $routes->get('stats/delete/(:num)', 'Admin\StatController::delete/$1');

    // Laporan
    $routes->get('reports/download', 'Admin\ReportController::download');
});
