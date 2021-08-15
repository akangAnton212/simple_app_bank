<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//import auth facades
use Illuminate\Support\Facades\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Models\MAccountCustomer;
use Illuminate\Support\Facades\Hash;

class AuthCustomerController extends Controller
{
    
     /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
          //validate incoming request 
        $this->validate($request, [
            'account_number'    => 'required|string',
            'password'          => 'required|string',
        ]);

        $credentials = $request->only(['account_number', 'password']);

        $account = MAccountCustomer::where([
                                'account_number'    => $request->input('account_number'),
                                'enabled'           => true
                            ])->first();

        if($account){
            if (Hash::check($request->input('password'), $account->password)) {
                if (!$token = Auth::guard('customer')->attempt($credentials)) {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }

                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ], 200);
            }else{
                response()->json(['message' => 'Unauthorized'], 401);
            }
        }else{
            response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    public function UserLogin(Request $request){
        // dd(Auth::guard('customer'));
        $user = Auth::guard('customer')->user()->customer;
        $account_login = Auth::guard('customer')->user();
        //return response()->json(['user' =>  $user], 200);
        return response()->json([
            'status'        => true,
            'message'       => 'Data Found',
            'data'          => $account_login
        ], 200);
    }
}
