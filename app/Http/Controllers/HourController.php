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

        $hour = Hour::create([
            'open' => $request->open,
            'close' => $request->close,
        ]);

        $day = Day::find($request->day);

        $hour->day()->attach($day);
        $hour->vendor()->attach($vendor);

    }

    public function update(Request $request)
    {

    }

    public function destroy(Request $request)
    {

    }

    private function authVendor()
    {
        $user = $this->getAuthUser();
        $vendor = $user->vendor()->get();

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
