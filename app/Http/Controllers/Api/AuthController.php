<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'firstname'=> 'required',
            'email' => 'required||email ||unique:customers',
            'lastname' => 'required',
            'username' => 'required || unique:customers'
        ]);
    }
}
