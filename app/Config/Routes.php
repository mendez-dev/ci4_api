<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

// Rutas para acceder a la documentación
$routes->group('documentation', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'DocumentationController::index', ['as' => 'documentation']);
    $routes->get('json', 'DocumentationController::json', ['as' => 'documentation_json']);
});

// Versión 1 de las api
$routes->group('v1', ['namespace' => 'App\Controllers'], function ($routes) {

    // Rutas de autenticación
    $routes->post('login', 'AuthController::index', ['as' => 'login']);
    $routes->get('verify', 'AuthController::verify', ['filter' => 'auth', 'as' => 'verify']);

    // Rutas de ajustes de la aplicación
    $routes->group('settings', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('/', 'AppSettingsController::index', ['as' => 'settings']);
        $routes->put('/', 'AppSettingsController::update', ['as' => 'update_settings']);
    });

    // Rutas administracion de usuarios
    $routes->group('user', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('/', 'UserController::index', ['filter' => 'auth', 'as' => 'users']);
        $routes->get('(:num)', 'UserController::info/$1', ['filter' => 'auth', 'as' => 'user']);
        $routes->post('/', 'UserController::store', ['filter' => 'auth', 'as' => 'user_store']);
        $routes->put('(:num)', 'UserController::update/$1', ['filter' => 'auth', 'as' => 'user_update']);
        $routes->delete('(:num)', 'UserController::delete/$1', ['filter' => 'auth', 'as' => 'user_delete']);
        $routes->put('(:num)/enable', 'UserController::enable/$1', ['filter' => 'auth', 'as' => 'user_enable']);
        $routes->put('(:num)/disable', 'UserController::disable/$1', ['filter' => 'auth', 'as' => 'user_disable']);
        $routes->put('(:num)/password', 'UserController::chagePassword/$1', ['filter' => 'auth', 'as' => 'user_password']);
    });

    // Rutas para la administracion de permisos
    $routes->group('permission', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('/', 'PermissionController::index', ['filter' => 'auth', 'as' => 'permissions']);
    });

    // Rutas para obtener el menu
    $routes->group('menu', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('/', 'MenuController::index', ['filter' => 'auth', 'as' => 'menu']);
    });
});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
