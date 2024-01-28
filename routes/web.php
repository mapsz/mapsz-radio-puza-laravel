<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Route::get('/test', function () {
//   echo 22;
// });


//Auth
Route::group([], function (){
  Auth::routes();
  // Auth::routes(['register' => false]);
  Route::get('/auth/user', function (){return response()->json(Auth::user());});
  // Route::get('/auth/user', function (){return response()->json(App\Models\User::with('roles')->find(Auth::user()->id));}); With roles
  Route::post('/json/logout', function () {Auth::logout();return response()->json(1);});
});

//Juge CRUD
Route::middleware(['auth'])->group(function (){

  {//Juge File Upload
    Route::post('/juge/file/upload',    [App\Http\Controllers\JugeFileUploadController::class, 'fileUpload']);
    Route::delete('/juge/file/delete',  [App\Http\Controllers\JugeFileUploadController::class, 'fileDelete']);
  }
  
  {//CRUD
    Route::get('/juge', 'App\Http\Controllers\JugeCRUDController@get');
    Route::get('/juge/keys', 'App\Http\Controllers\JugeCRUDController@getKeys');
    Route::get('/juge/inputs', 'App\Http\Controllers\JugeCRUDController@getInputs');    
    Route::get('/juge/post/inputs', 'App\Http\Controllers\JugeCRUDController@getPostInputs');    
    Route::put('/juge', 'App\Http\Controllers\JugeCRUDController@put');
    Route::post('/juge', 'App\Http\Controllers\JugeCRUDController@post');
    Route::delete('/juge', 'App\Http\Controllers\JugeCRUDController@delete'); 
  }
  
  //Config
  Route::post('/juge/crud/settings', 'App\Http\Controllers\JugeCRUDController@postConfig');

});

Route::post('/login/as', 'App\Http\Controllers\UserController@loginAsUser');

//Vue
Route::group(['middleware' => ['auth']], function (){

  //Vue Pages
  Route::get('/{vue_capture?}', function () {return view('main');})->where('vue_capture', '[\/\w\.-]*');


  Route::get('/admin/{vue_capture?}', function () {return view('main');})->where('vue_capture', '[\/\w\.-]*');

});
