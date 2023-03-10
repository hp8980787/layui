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

//Route ::get('/', function () {
//    return view('index');
//});
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

        Route ::name('permissions.') -> group(function () {
            Route ::any('permissions', [C\PermissionController::class, 'index']) -> name('index');
            Route ::get('permissions/create', [C\PermissionController::class, 'create']) -> name('create');
            Route ::post('permissions/store', [C\PermissionController::class, 'store']) -> name('store');
            Route ::get('permissions/{id}/edit', [C\PermissionController::class, 'edit']) -> name('edit');
            Route ::put('permissions/update', [C\PermissionController::class, 'update']) -> name('update');
            Route ::delete('permissions/delete', [C\PermissionController::class, 'destroy']) -> name('destroy');
        });

        Route ::name('roles.') -> group(function () {
            Route ::any('roles', [C\RoleController::class, 'index']) -> name('index');
            Route ::get('roles/create', [C\RoleController::class, 'create']) -> name('create');
            Route ::post('roles/store', [C\RoleController::class, 'store']) -> name('store');
            Route ::get('roles/{id}/edit', [C\RoleController::class, 'edit']) -> name('edit');
            Route ::put('roles/update', [C\RoleController::class, 'update']) -> name('update');
            Route ::delete('roles/delete', [C\RoleController::class, 'destroy']) -> name('destroy');
        });

        Route ::name('files.') -> group(function () {
            Route ::any('files', [C\FileController::class, 'index']) -> name('index');
            Route ::get('files/create', [C\FileController::class, 'crate']) -> name('create');
            Route ::post('files/store', [C\FileController::class, 'store']) -> name('store');
            Route ::get('files/{id}/edit', [C\FileController::class, 'edit']) -> name('edit');
            Route ::put('files/update', [C\FileController::class, 'update']) -> name('update');
            Route ::delete('files/delete', [C\FileController::class, 'destroy']) -> name('destroy');

        });
        Route ::post('upload-files', [C\FileController::class, 'upload']) -> name('files.upload');

        Route ::name('domains.') -> prefix('domains') -> group(function () {
            Route ::any('/', [C\DomainController::class, 'index']) -> name('index');
            Route ::post('/check', [C\DomainController::class, 'check']) -> name('check');
            Route ::get('/create', [C\DomainController::class, 'create']) -> name('create');
            Route ::put('/update', [C\DomainController::class, 'update']) -> name('update');
            Route ::get('/{id}/edit', [C\DomainController::class, 'edit']) -> name('edit');
            Route ::delete('/delete', [C\DomainController::class, 'destroy']) -> name('destroy');
            Route ::post('restore', [C\DomainController::class, 'restore']) -> name('restore');
            Route ::post('store', [C\DomainController::class, 'store']) -> name('store');
        });

        Route ::name('servers.') -> prefix('servers') -> group(function () {
            Route ::any('/', [C\ServerController::class, 'index']) -> name('index');
            Route ::get('create', [C\ServerController::class, 'create']) -> name('create');
            Route ::post('store', [C\ServerController::class, 'store']) -> name('store');
            Route ::get('{id}/edit', [C\ServerController::class, 'edit']) -> name('edit');
            Route ::put('update', [C\ServerController::class, 'update']) -> name('update');

        });

        Route ::name('countries.') -> prefix('countries') -> group(function () {
            Route ::any('/', [C\CountryController::class, 'index']) -> name('index');
            Route ::put('/update', [C\CountryController::class, 'update']) -> name('update');
        });

        Route ::post('clear-cache', [C\DashboardController::class, 'clear']) -> name('clear-cache');
    });

    Route ::name('categories.') -> prefix('categories') -> group(function () {
        Route ::any('/', [C\CategoriesController::class, 'index']) -> name('index');
        Route ::any('/{id}/edit', [C\CategoriesController::class, 'edit']) -> name('edit');
        Route ::put('update', [C\CategoriesController::class, 'update']) -> name('update');
        Route ::get('create', [C\CategoriesController::class, 'create']) -> name('create');
        Route ::post('store', [C\CategoriesController::class, 'store']) -> name('store');
        Route ::delete('/delete', [C\CategoriesController::class, 'destroy']) -> name('destroy');
    });

});

Route ::prefix('api') -> group(function () {
    Route ::get('get-ip-info', [\App\Http\Controllers\IpController::class, 'index']);
});
