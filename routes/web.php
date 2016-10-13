<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'RegionController@index');

Route::get('scraper', 'ScraperController@scrapeData');

Route::get('region/{region}', 'RegionController@displayRegion');

Route::get('player/{id}', function ($id) {
    $player = App\Player::find($id);
    $data = array(
        'player' => $player
        );
    return view('layouts.player', $data);
});