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

Auth::routes();

Route::get("/test", 'GameController@test');

Route::get("/library", 'GameController@showLibrary')->name("games.library");
Route::get("/inventory", 'GameController@showInventory')->name("games.inventory");
Route::get('/search', 'GameController@search');

Route::get('/games/sync_applist_from_steam', 'GameController@SyncApplistTest');
Route::get('/games/store/{id}', 'GameController@storeApp');
Route::resource('games', 'GameController');


Route::resource('purchases', 'PurchaseController');
Route::post('/purchase/get_purchases', 'PurchaseController@getPurchases');
Route::post('/purchase/update_availability', 'PurchaseController@updateAvailability');
Route::post('/purchase/update_purchase_price', 'PurchaseController@updatePurchasePrice');
Route::post('/purchase/update_purchase_item_price', 'PurchaseController@updatePurchaseItemPrice');
Route::post('/purchase/insert_game', 'PurchaseController@insertGamesIntoPurchase');
Route::post('/purchase/insert_bundle', 'PurchaseController@addBundleToPurchase');
Route::post('/purchase/action', 'PurchaseController@massAction');

Route::resource('bundles', 'BundleController');
Route::post('/bundles/change_tier','BundleController@changeTier')->name("bundle.change_tier");
Route::post('/bundles/link_game_bundle', 'BundleController@linkGameToBundle')->name("bundle.link_game_bundle");

Route::get("/settings", "SettingsController@index");
Route::get("settings/g2a", "SettingsController@showG2aSettings");
Route::post("settings/toggle_g2a", "SettingsController@toggleG2a")->name("settings.toggle_g2a");
Route::get("settings/g2a_auto_matcher", "SettingsController@g2aAutoMatcher");
Route::post("settings/auto_match", "GameController@g2aAutoMatch");
Route::get("settings/g2a_price_updater", "SettingsController@g2aPriceUpdater");
Route::post("settings/g2a_update_price", "GameController@g2aUpdatePrice");
Route::get("settings/myaccount", "SettingsController@showMyAccount");
Route::get("settings/update_database", "GameController@updateDatabase");

Route::get("/", "GameController@index");
//Route::get("/", "GameController@home");


