<?php
use Illuminate\Support\Facades\Auth;

Route::get('/series', 'SeriesController@index')->name('listar-series');
Route::get('/series/criar', 'SeriesController@create')->name('criar-serie')->middleware('autenticador');;
Route::post('/series/criar', 'SeriesController@store')->middleware('autenticador');;
Route::delete('/series/{id}', 'SeriesController@destroy')->middleware('autenticador');;
Route::post('/series/{id}/editaNome', 'SeriesController@editaNome')->middleware('autenticador');;

Route::get('/series/{serieId}/temporadas', 'TemporadasController@index');

Route::get('/temporada/{temporada}/episodios', 'EpisodiosController@index');
Route::post('/temporada/{temporada}/episodios/assistir', 'EpisodiosController@assistir')->middleware('autenticador');;
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/entrar', 'EntrarController@index');
Route::post('/entrar', 'EntrarController@entrar');

Route::get('/registrar', 'RegistroController@create');
Route::post('/registrar', 'RegistroController@store');

Route::get('/sair', function(){
    Auth::logout();
    return redirect('/entrar');
});
