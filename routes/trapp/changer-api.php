<?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('trapp/owningtype', 'trapp\\API\\owningtypeController@add');
    Route::put('trapp/owningtype/{id}', 'trapp\\API\\owningtypeController@update');
    Route::delete('trapp/owningtype/{id}', 'trapp\\API\\owningtypeController@delete');
});
?><?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('trapp/areatype', 'trapp\\API\\areatypeController@add');
    Route::put('trapp/areatype/{id}', 'trapp\\API\\areatypeController@update');
    Route::delete('trapp/areatype/{id}', 'trapp\\API\\areatypeController@delete');
});
?><?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('trapp/viewtype', 'trapp\\API\\viewtypeController@add');
    Route::put('trapp/viewtype/{id}', 'trapp\\API\\viewtypeController@update');
    Route::delete('trapp/viewtype/{id}', 'trapp\\API\\viewtypeController@delete');
});
?><?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('trapp/structuretype', 'trapp\\API\\structuretypeController@add');
    Route::put('trapp/structuretype/{id}', 'trapp\\API\\structuretypeController@update');
    Route::delete('trapp/structuretype/{id}', 'trapp\\API\\structuretypeController@delete');
});
?><?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('trapp/villa/reservestart/{id}', 'trapp\\API\\villaController@StartReservePayment');
    Route::get('trapp/villa/reservebyowner/{id}', 'trapp\\API\\villaController@reserveByOwner');
    Route::get('trapp/villa/removereservebyowner/{id}', 'trapp\\API\\villaController@removeReserveByOwner');
    Route::post('trapp/villa', 'trapp\\API\\villaController@add');
    Route::get('trapp/userfullinfo', 'trapp\\API\\villaController@getUserFullInfo');
    Route::put('trapp/villa/{id}', 'trapp\\API\\villaController@update');
    Route::delete('trapp/villa/{id}', 'trapp\\API\\villaController@delete');


//------------------------------------------------------------------------------------------------------
});
?>
<?php

?>
<?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('trapp/order', 'trapp\\API\\orderController@add');
    Route::put('trapp/order/{id}', 'trapp\\API\\orderController@update');
    Route::delete('trapp/order/{id}', 'trapp\\API\\orderController@delete');
//------------------------------------------------------------------------------------------------------
    Route::get('trapp/order', 'trapp\\API\\orderController@list');
    Route::get('trapp/order/user', 'trapp\\API\\orderController@UserOrdersList');
    Route::get('trapp/order/villa', 'trapp\\API\\orderController@villaorderslist');
    Route::get('trapp/order/{id}', 'trapp\\API\\orderController@get');
});
?>


<?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('trapp/villaowner', 'trapp\\API\\villaownerController@add');
    Route::put('trapp/villaowner/{id}', 'trapp\\API\\villaownerController@update');
    Route::delete('trapp/villaowner/{id}', 'trapp\\API\\villaownerController@delete');

    Route::get('trapp/villaowner/byuser/{id}', 'trapp\\API\\villaownerController@getFromUser');
//------------------------------------------------------------------------------------------------------
    Route::get('trapp/villaowner', 'trapp\\API\\villaownerController@list');
    Route::get('trapp/villaowner/{id}', 'trapp\\API\\villaownerController@get');
});
?>
<?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('trapp/option', 'trapp\\API\\optionController@add');
    Route::put('trapp/option/{id}', 'trapp\\API\\optionController@update');
    Route::delete('trapp/option/{id}', 'trapp\\API\\optionController@delete');
});
?><?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('trapp/villaoption', 'trapp\\API\\villaoptionController@add');
    Route::put('trapp/villaoption/{id}', 'trapp\\API\\villaoptionController@update');
    Route::delete('trapp/villaoption/{id}', 'trapp\\API\\villaoptionController@delete');
    Route::put('trapp/villaoption/byvilla/{VillaID}', 'trapp\\API\\villaoptionController@saveVillaOptions');
});
?>
<?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function() {
    Route::put('trapp/villanonfreeoption/byvilla/{VillaID}', 'trapp\\API\\villanonfreeoptionController@saveVillaOptions');
    Route::post('trapp/villanonfreeoption', 'trapp\\API\\villanonfreeoptionController@add');
    Route::put('trapp/villanonfreeoption/{id}', 'trapp\\API\\villanonfreeoptionController@update');
    Route::delete('trapp/villanonfreeoption/{id}', 'trapp\\API\\villanonfreeoptionController@delete');
});
?><?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function() {
    Route::post('trapp/ordervillanonfreeoption', 'trapp\\API\\ordervillanonfreeoptionController@add');
    Route::put('trapp/ordervillanonfreeoption/{id}', 'trapp\\API\\ordervillanonfreeoptionController@update');
    Route::delete('trapp/ordervillanonfreeoption/{id}', 'trapp\\API\\ordervillanonfreeoptionController@delete');
});
?>
