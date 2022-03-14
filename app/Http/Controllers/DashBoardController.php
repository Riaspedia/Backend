<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{

    public function listVendor() {
        $list = Vendor::all()->take(10) ;

        return response()->json([
            "list" => $list
        ], 200);
    }


    private function getAuthUser()
    {
        try{
            return $user = auth()->userOrFail();
        }catch(\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e){
            response()->json([
                'message' => 'Not authenticated, please login first'
            ], 401)->send();
            exit;
        }
    }
}
