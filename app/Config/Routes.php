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
    $routes->group('app_settings', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('/', 'AppSettingsController::index', ['as' => 'settings']);
        $routes->put('/', 'AppSettingsController::update', ['as' => 'update_settings']);
    });

    // Rutas administracion de usuarios
    $routes->group('user', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('/', 'UserController::index', ['filter' => 'auth:USER_READ', 'as' => 'users']);
        $routes->get('(:segment)', 'UserController::info/$1', ['filter' => 'auth:USER_READ', 'as' => 'user']);
        $routes->post('/', 'UserController::store', ['filter' => 'auth:USER_CREATE', 'as' => 'user_store']);
        $routes->put('(:segment)', 'UserController::update/$1', ['filter' => 'auth:USER_UPDATE', 'as' => 'user_update']);
        $routes->delete('(:segment)', 'UserController::delete/$1', ['filter' => 'auth:USER_DELETE', 'as' => 'user_delete']);
        $routes->put('(:segment)/enable', 'UserController::enable/$1', ['filter' => 'auth:USER_ENABLE', 'as' => 'user_enable']);
        $routes->put('(:segment)/disable', 'UserController::disable/$1', ['filter' => 'auth:USER_DISABLE', 'as' => 'user_disable']);
        $routes->put('(:segment)/password', 'UserController::chagePassword/$1', ['filter' => 'auth:USER_PASSWORD', 'as' => 'user_password']);
    });

    // Rutas administracion de grupos
    $routes->group('group', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('/', 'GroupController::index', ['as' => 'groups']);
        $routes->get('(:segment)', 'GroupController::info/$1', ['as' => 'group']);
        $routes->post('/', 'GroupController::store', ['filter' => 'auth', 'as' => 'group_store']);
        $routes->put('(:segment)', 'GroupController::update/$1', ['filter' => 'auth', 'as' => 'group_update']);
        $routes->delete('(:segment)', 'GroupController::delete/$1', ['filter' => 'auth', 'as' => 'group_delete']);
        $routes->put('(:segment)/enable', 'GroupController::enable/$1', ['filter' => 'auth', 'as' => 'group_enable']);
        $routes->put('(:segment)/disable', 'GroupController::disable/$1', ['filter' => 'auth', 'as' => 'group_disable']);
        $routes->get('(:segment)/permissions', 'GroupController::permissions/$1', ['filter' => 'auth', 'as' => 'group_permissions']);
        $routes->put('(:segment)/permissions', 'GroupController::assignPermissions/$1', ['filter' => 'auth', 'as' => 'group_permissions_update']);
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
