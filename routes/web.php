<?php

Route::get('/series', 'SeriesController@index')->name('listar-series');
Route::get('/series/criar', 'SeriesController@create')->name('criar-serie');
Route::post('/series/criar', 'SeriesController@store');
Route::delete('/series/{id}', 'SeriesController@destroy');


Route::get('/series/{serieId}/temporadas', 'TemporadasController@index');
