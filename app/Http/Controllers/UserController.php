<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  public function loginAsUser(Request $request){
    Auth::loginUsingId($request->id);
    return response()->json(1, 200);
  }
}
