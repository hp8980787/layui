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
        Route::any('users',[C\UserController::class ,'index'])->name('index');
        Route::any('users/create',[C\UserController::class ,'create'])->name('create');
    });


});
