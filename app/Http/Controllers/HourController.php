<?php

namespace App\Http\Controllers;

use App\Models\Day;
use Illuminate\Http\Request;
use App\Models\Hour;

class HourController extends Controller
{
    public function store(Request $request)
    {
        $vendor = $this->authVendor();

        $request->validate([
            "open" => "required",
            "close" => "required",
        ]);

        $hour = new Hour;
        $hour->open = $request->open;
        $hour->close = $request->close;

        $day = Day::find($request->day_id);
        $hour->day()->associate($day);
        $hour->vendor()->associate($vendor);
        $hour->save();

        return response()->json([
            "message" => "create hour successfully"
        ], 201);
    }

    public function update(Request $request)
    {
        $vendor = $this->authVendor();
        $hour = $vendor->hours()->find($request->id)->get();

        if (!empty($hour)) {
            $hour->open = is_null($request->open) ? $hour->open : $request->open;
            $hour->close =is_null($request->close) ? $hour->close : $request->close;
            $hour->save();

            return response()->json([
                "message" => "hour updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "hour not found"
            ], 404);
        }

    }

    public function destroy(Request $request)
    {

    }

    private function authVendor()
    {
        $user = $this->getAuthUser();
        $vendor = $user->vendor;

        if(!empty($vendor)) {
            return $vendor;
        } else {
            response()->json([
                "message" => "vendor not found"
            ], 404);
            exit;
        }
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
