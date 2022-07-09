<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
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
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        $token->expires_at = $request->remember_me ? Carbon::now()->addWeeks(1) : Carbon::now()->addDay(1);
        $token->save();
        $access_token = $tokenResult->accessToken;
        $token_type = 'Bearer';
        $expires_at = Carbon::parse(
            $tokenResult->token->expires_at
        )->toDateTimeString();
        
        return response()->json([
            'succes' => $user,
            'message' => 'user enregistrer',
            'access_token' => $access_token,
            'token_type' => $token_type,
            'expires_at' => $expires_at
        ]);
    }
    public function login(Request $request)
    {
        $login_type = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = Customers::where($login_type, $request->email)->first();
        if(!Hash::check($request->password, $user->password)){
            return [
               'error' => 'Credentials wrong',
               'message' => 'Credentials wrong',
               'status_code' => 401
           ];
        }
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        $token->expires_at = $request->remember_me ? Carbon::now()->addWeeks(1) : Carbon::now()->addDay(1);
        $token->save();
        $access_token = $tokenResult->accessToken;
        $token_type = 'Bearer';
        $expires_at = Carbon::parse(
            $tokenResult->token->expires_at
        )->toDateTimeString();
        return response()->json([
            'access_token' => $access_token,
            'token_type' => $token_type,
            'expires_at' => $expires_at
        ], 200);
    }
}
