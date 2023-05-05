<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('home');
});

Route::get('/home', function () {
    if (Auth::check()) {
        $users = User::all();
        return view('home', compact("users"));
    }
    return redirect("login");
});

Route::view("/register", "register");
Route::post("/register", function (Request $request) {
    $request["password"] = Hash::make($request['password']);
    User::create($request->all());
    return redirect("login")->with('msg', 'You are a user now');
});

Route::view("/login", "login");
Route::post("/login", function (Request $request) {
    if (Auth::attempt($request->only('email', 'password')))
        return redirect('home');
    return redirect("login");
});

Route::get("/logout", function () {
    Session::flush();
    Auth::logout();
    return redirect('home');
});
