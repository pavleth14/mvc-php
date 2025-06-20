<?php
require_once('./Router.php');
require_once('./app/Controllers/HomeController.php');
require_once('./app/Controllers/InvoiceController.php');

define('STORAGE_PATH', __DIR__ . '/storage');
define('VIEW_PATH', __DIR__ . '/views');

try {
$router = new Router();
$router
    // registrovanje rute
    // Ako dođe zahtev na /, pozovi metodu index() iz klase App\Classes\Home
    ->get('/', [App\Controllers\HomeController::class, 'index']) 
    ->get('/download', [App\Controllers\HomeController::class, 'download']) 
    ->post('/upload', [App\Controllers\HomeController::class, 'upload'])
    ->get('/invoices', [App\Classes\InvoiceController::class, 'index'])
    ->get('/invoices/create', [App\Classes\InvoiceController::class, 'create'])
    ->post('/invoices/create', [App\Classes\InvoiceController::class, 'store']);

// $router->register(
//     '/invoices',
//     function() {
//     echo 'Invoices'; 
// });


// Rešava zahtev i izvršava akciju
echo $router->resolve($_SERVER['REQUEST_URI'], strtolower($_SERVER['REQUEST_METHOD']));
} catch(\App\RouteNotFoundException $e) {
    // header('HTTP/1.1 404 Not Found');
    http_response_code(404);
    echo \App\View::make('error/404');
}