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
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/login', 'Login::index');
$routes->get('/logout', 'Login::logout');
$routes->get('/register', 'Registration::index');
$routes->get('/homepage', 'Homepage::index');
$routes->get('/admin', 'Admin::index');
$routes->get('/loginCheck/(.+)', 'Login::loginCheck/$1');
$routes->get('/regCheck/(.+)', 'Registration::regCheck/$1');
$routes->get('/newCategory/(.+)', 'Admin::newCategory/$1');
$routes->get('/subcategory/(:any)/(:num)', 'Admin::newSub/$1/$2');
$routes->get('/newProduct/(.+)', 'Admin::newProduct/$1/$2/$3/$4');
$routes->get('/wallet/(:num)/(:num)', 'Homepage::wallet/$1/$2');
$routes->get('/newPayment/(:any)', 'Admin::newPayment/$1/$2');
$routes->get('/receipt/(:num)', 'Homepage::receipt/$1');
// $routes->match(['get', 'post'], 'frontend/login', 'Form::index');

# API ROUTES === USERS
$routes->get('users','Users::index');
$routes->get('users/(:num)','Users::show/$1');
$routes->post('users','Users::byEmail');

# API ROUTES === PRODUCTS
$routes->get('products','Products::index');
$routes->get('products/(:num)','Products::show/$1');
$routes->get('products/(.+)','Products::search/$1/$2');

# API ROUTES === TRANSACTIONS
$routes->get('transactions','Transactions::index');
$routes->get('transactions/(:num)','Transactions::show/$1');
$routes->get('transactions/(.+)','Transactions::search/$1/$2');


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
