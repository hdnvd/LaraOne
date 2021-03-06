<?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('placeman/place', 'placeman\\API\\PlaceController@add');
    Route::put('placeman/place/{id}', 'placeman\\API\\PlaceController@update');
    Route::put('placeman/place/{id}/activate/{type}', 'placeman\\API\\PlaceController@activate');
    Route::delete('placeman/place/{id}', 'placeman\\API\\PlaceController@delete');
});
Route::group(['middleware' => 'auth:api'], function () {

    Route::get('/placeman/branches/userinfo', 'placeman\\API\\PlacemanController@getUserBranch');
    Route::post('/placeman/places/edit', 'placeman\\API\\PlacemanController@edit');
});
?>
<?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('placeman/placephoto', 'placeman\\API\\placephotoController@add');
    Route::get('placeman/placephoto/place/{id}', 'placeman\\API\\placephotoController@listPlacePhotos');
    Route::put('placeman/placephoto/{id}', 'placeman\\API\\placephotoController@update');
    Route::delete('placeman/placephoto/{id}', 'placeman\\API\\placephotoController@delete');
});
?><?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('placeman/phototype', 'placeman\\API\\phototypeController@add');
    Route::put('placeman/phototype/{id}', 'placeman\\API\\phototypeController@update');
    Route::delete('placeman/phototype/{id}', 'placeman\\API\\phototypeController@delete');
});
?>
<?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('placeman/province', 'placeman\\API\\provinceController@add');
    Route::put('placeman/province/{id}', 'placeman\\API\\provinceController@update');
    Route::delete('placeman/province/{id}', 'placeman\\API\\provinceController@delete');
});
?><?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('placeman/city', 'placeman\\API\\cityController@add');
    Route::put('placeman/city/{id}', 'placeman\\API\\cityController@update');
    Route::delete('placeman/city/{id}', 'placeman\\API\\cityController@delete');
});
?><?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('placeman/area', 'placeman\\API\\areaController@add');
    Route::put('placeman/area/{id}', 'placeman\\API\\areaController@update');
    Route::delete('placeman/area/{id}', 'placeman\\API\\areaController@delete');
});
?>
