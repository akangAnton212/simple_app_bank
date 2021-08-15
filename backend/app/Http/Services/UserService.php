<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;

use App\Models\MUser;

class UserService {
    public function userProfile($request){
        try {
            return response()->json([
                'status'        => true,
                'message'       => 'Data Found',
                'data'          => $request->user()
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'message'       => $e->getMessage(),
                'data'          => null
            ], $e->getCode());
        }
    }
}