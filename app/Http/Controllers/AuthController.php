<?php

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin(){
        return view('login');
    }

    public function login(Request $request){
        $credentials = $request->only('email','password');

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $role = auth()->user()->role;
            if($role === 'superadmin'){
                return redirect('/superadmin');
            } else {
                return redirect('/superuser');
            }
        }

        return back()->withErrors([
            'email'=>'Invalid credentials'
        ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

