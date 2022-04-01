<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->userOrFail();;
        $vendor = $user->vendor;
        if(!empty($user)){
            return $this->createNewToken($token, $user);
        } else {
            response()->json([
                'message' => 'Not authenticated'
            ], 401)->send();
            exit;
        }
    }

    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    protected function createNewToken($token, $user){
        $vendor = Vendor::where('user_id', Auth()->user()->id)->first();
        $id = "";
        if($vendor) {
            $id = $vendor->id;
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth()->factory()->getTTL() * 60,
            'user_id' => Auth()->user()->id,
            'vendor_id' => $id,
        ]);
    }
}
