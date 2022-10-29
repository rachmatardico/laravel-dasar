<?php

use App\Exceptions\ValidationException;
use App\Http\Controllers\CookieController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\SessionController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pzn', function(){
    return "Hello Programmer Zaman Now";
});

Route::redirect('/youtube', '/pzn');

Route::fallback(function(){
    return "404 by Programmer Zaman Now";
});

Route::view('/hello', 'hello', ['name' => 'Rachmat']);

Route::get('/hello-again', function(){
    return view('hello', ['name' => "Rachmat"]);
});

Route::get('/hello-world', function(){
    return view('hello.world', ['name' => "Rachmat"]);
});

Route::get('/products/{id}', function($productId){
    return "Product : $productId";
})->name("product.detail");

Route::get('/products/{id}/items/{item}', function($productId, $itemId){
    return "Product : $productId, Item : $itemId";
})->name("product.item.detail");

// Route Dengan Regular Expression
Route::get('/categories/{id}', function($categoryId){
    return "Category : $categoryId"; 
})->where('id', '[0-9]+')->name("category.detail");

// Optional Route Parameter
Route::get('/users/{id?}', function($userId = '404'){
    return "User : $userId";
})->name("user.detail");

// Route Conflict
Route::get('/conflict/rachmat', function(){
    return "Conflict : Rachmat Ardico";
});

Route::get('/conflict/{name}', function($name){
    return "Conflict : $name";
});

Route::get('/produk/{id}', function($id){
    $link = route('product.detail', ['id' => $id]);
    return "Link : $link";
});

Route::get('/produk-redirect/{id}', function($id){
    return redirect()->route('product.detail', ['id' => $id]);
});

// Request
Route::get('/controller/hello/request', [HelloController::class, 'request']);
Route::get('/controller/hello/{name}', [HelloController::class, 'hello']);

// Request Input
Route::prefix('input')->controller(InputController::class)
    ->group(function(){
        Route::get('hello', 'hello');
        Route::post('hello', 'hello');

        Route::prefix('hello')->group(function(){
            Route::post('first', 'helloFirstName');
            Route::post('input', 'helloInput');
            Route::post('array', 'helloArray');
        });
        Route::post('type', 'inputType');
        
        // Request Filter Input
        Route::prefix('filter')->group(function(){
                Route::post('only', 'filterOnly');
                Route::post('except', 'filterExcept');
                Route::post('merge', 'filterMerge');
        });
});

// File Upload
Route::post('/file/upload', [FileController::class, 'upload'])
    ->withoutMiddleware([VerifyCsrfToken::class]);

// Response
Route::prefix('response')->controller(ResponseController::class)
    ->group(function(){
        Route::get('hello', 'response');
        Route::get('header', 'header');

        Route::prefix('type')->group(function(){
            Route::get('view', 'responseView');
            Route::get('json', 'responseJson');
            Route::get('file', 'responseFile');
            Route::get('download', 'responseDownload');
        });
});

// Cookie
Route::prefix('cookie')->controller(CookieController::class)
    ->group(function(){
        Route::get('set', 'createCookie');
        Route::get('get', 'getCookie');
        Route::get('clear', 'clearCookie');
});

// Redirect
Route::prefix('redirect')->controller(RedirectController::class)
    ->group(function(){
        Route::get('from', 'redirectFrom');
        Route::get('to', 'redirectTo');
        Route::get('name', 'redirectName');
        Route::get('name/{name}', 'redirectHello')
            ->name('redirect-hello');
        Route::get('/named', function(){
            return route('redirect-hello', ['name' => 'Matt']);
        });

        Route::get('action', 'redirectAction');
        Route::get('pzn', 'redirectAway');
});

// Middleware
Route::middleware(['contoh:PZN,401'])->prefix('middleware')
    ->group(function(){
        Route::get('api', function(){
            return "OK";
        });
        
        Route::get('group', function(){
            return "GROUP";
        });
});

// CSRF
Route::get('/form', [FormController::class, 'form']);
Route::post('/form', [FormController::class, 'submitForm']);

// URL Generation
Route::get('/url/current', function(){
    return URL::full();
});

Route::get('/url/action', function(){
    // return action([FormController::class, 'form'], []);
    // return url()->action([FormController::class, 'form'], []);
    return URL::action([FormController::class, 'form'], []);
});

// Session
Route::prefix('session')->group(function () {
    Route::get('create', [SessionController::class, 'createSession']);
    Route::get('get', [SessionController::class, 'getSession']);
});

// Error Handling
Route::prefix('error')->group(function () {
    Route::get('sample', function(){
        throw new Exception("Sample Error");
    });
    
    Route::get('manual', function () {
        report(new Exception("Sample Error"));
        return "OK";
    });

    Route::get('validation', function () {
        throw new ValidationException("Validation Error");
    });
});