<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    public function detailVendor($id) {
        $vendor = Vendor::find($id);
        $hour = $vendor->hours()->get();
        $services = $vendor->services()->get();
        $reviews = $vendor->reviews()->get();
        $gallery = $vendor->galleries()->get();
        foreach ($hour as $themp){
            $day[] = [
                "open"=> $themp->open,
                "close"=> $themp->close,
                "day"=> $themp->day()->get('name')];
        }

        return response()->json([
            "vendor" => $vendor,
            "day and hour" => $day,
            "Service" => $services,
            "reviews" => $reviews,
            "gallery" => $gallery,
        ], 201);
    }

    public function listVendor() {
        $list = Vendor::all()->take(5);

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
