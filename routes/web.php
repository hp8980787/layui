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
Route::get('/', function () {
    return view('index');
});
Route::prefix('admin')->as('admin.')->middleware(['admin.locale'])->group(function (){
   Route::resource('menus',C\MenuController::class)->except('show');
});
