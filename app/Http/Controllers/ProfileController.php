<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    public function update(Request $request)
    {
        $user = $this->getAuthUser();
        if (!empty($service)) {
            $user->name = is_null($request->name) ? $user->name : $request->name;
            $user->gender = is_null($request->gender) ? $user->gender : $request->gender;
            $user->city = is_null($request->city) ? $user->city : $request->city;
            $user->province = is_null($request->province) ? $user->province : $request->province;
            $user->save();

            return response()->json([
                "message" => "Update profile successfully"
            ], 201);
        } else {
            return response()->json([
                "message" => "update profile failed"
            ], 400);
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
