<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $data = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ];
        if (isset($request->sexe)) {
            $data['sexe'] = $request->sexe;
        }
        $user = Customers::create($data);
        return response()->json([
            'succes' => $user,
            'message' => 'user enregistrer'
        ]) ;
    }
}
