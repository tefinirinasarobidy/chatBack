<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return response()->json([
            'succes' => $user,
            'message' => 'user enregistrer'
        ]);
    }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            $login_type = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $user_email = Customers::where($login_type, $request->email)->first();
            if (!Hash::check($request->password, $user_email->password)) {
                return [
                    'error' => 'Credentials wrong',
                    'message' => 'Credentials wrong',
                    'status_code' => 401
                ];
            }
            $tokenResult = $user_email->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;
            $token_type = 'Bearer';
            
            return response()->json(
                [
                    'token_type' => $token_type,
                    'token' => $token
                ],
                200
            );
        } catch (Exception $e) {
            return [
                'error' => 'something_went_wrong',
                'message' => $e->getMessage(),
                'status_code' => 500
            ];
        }
    }
    public function logout()
    {
        try{
            auth()->user()->tokens()->delete();
    
            return [
                'message' => 'You have successfully logged out and the token was successfully deleted'
            ];

        } catch (Exception $e) {
            return [
                'error' => 'something_went_wrong',
                'message' => $e->getMessage(),
                'status_code' => 500
            ];
        }
       
    }
}
