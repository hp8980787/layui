<?php

use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\Admin as C;

Route ::get('/', function () {
    return view('index');
});
Route ::prefix('admin') -> as('admin.') -> middleware(['admin.locale']) -> group(function () {

    Route ::any('users/login', [C\UserController::class, 'login']) -> name('users.login');
    Route ::middleware(['auth']) -> group(function () {
        Route ::get('/dashboard', [C\DashboardController::class, 'index']) -> name('/');
        Route ::name('menus.') -> group(function () {
            Route ::post('api/menus', [C\MenuController::class, 'index']) -> name('api');
            Route ::get('menus', [C\MenuController::class, 'index']) -> name('index');
            Route ::get('menus/create', [C\MenuController::class, 'create']) -> name('create');
            Route ::post('menus', [C\MenuController::class, 'store']) -> name('store');
            Route ::match(['get', 'post'], 'menus/edit', [C\MenuController::class, 'edit']) -> name('edit');
            Route ::delete('menus/delete', [C\MenuController::class, 'destroy']) -> name('destroy');
            Route ::get('menus-all', [C\MenuController::class, 'all']) -> name('all');
        });
        Route ::name('users.') -> group(function () {
            Route ::any('users', [C\UserController::class, 'index']) -> name('index');
            Route ::any('users/create', [C\UserController::class, 'create']) -> name('create');
            Route ::post('users/store', [C\UserController::class, 'store']) -> name('store');
            Route ::get('users/{id}/edit', [C\UserController::class, 'edit']) -> name('edit');
            Route ::put('users/update', [C\UserController::class, 'update']) -> name('update');
            Route ::get('users/logout', [C\UserController::class, 'logout']) -> name('logout');
            Route ::delete('users/{id}', [C\UserController::class, 'destroy']) -> name('destroy');
        });

        Route ::post('upload-files', [C\FileController::class, 'upload']) -> name('files.upload');
    });


});
