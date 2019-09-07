<?php

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

/*
|-------------------------------------------------------------
| Actions Handled By Resource Controller
|-------------------------------------------------------------
|
| Verb 		URI						Action		Route Name
| GET		/photos					index		photos.index
| GET		/photos/create			create		photos.create
| POST		/photos					store		photos.store
| GET		/photos/{photo}			show		photos.show
| GET		/photos/{photo}/edit	edit		photos.edit
| PUT/PATCH	/photos/{photo}			update		photos.update
| DELETE	/photos/{photo}			destroy		photos.destroy
|
*/

Route::get('/teste', 'AATeste');

Route::get('/', function () {
    return view('articles/jogoDoRefresco');
});

/*
* Ensaio do novo layout
*/
Route::get('/ensaio', function () {
    return view('teste');
});

Auth::routes(['verify' => true]);

Route::resource('games', 'GameController');
Route::get('/games/join/{id}', 'GameController@join');
Route::get('/games/leave/{id}', 'GameController@leave');
Route::get('/games/manage/{id}', 'GameManage')->name('games.manage');
Route::get('/games/process/{id}', 'GameProcess')->name('games.process');
Route::get('/games/finish/{id}', 'GameFinish')->name('games.finish');
Route::get('/games/play/{id}', 'GamePlay')->name('games.play');
Route::post('/games/inboundOrder', 'InboundOrderDeliver');
Route::post('/games/productionOrder', 'ProductionOrderDeliver');
Route::post('/games/outboundOrder', 'OutboundOrderDeliver');
Route::post('/games/cleanupOrder', 'CleanupOrderDeliver');

Route::resource('rules', 'RuleSetController');

Route::get('/credits', function () {
    return view('welcome');
});
Route::get('/cookies', function () {
    return view('welcome');
});
Route::get('/privacy', function () {
    return view('welcome');
});


Route::get('/articles/pedagogia', function () {
    return view('/articles/pedagogiaDosJogos');
});

Route::get('/articles/jogoDoRefresco', function () {
    return view('/articles/jogoDoRefresco');
});

Route::get('/articles/jogoDoTransporte', function () {
    return view('/articles/jogoDoTransporte');
});
