<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
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
        $user = $user->find($request->id);

        if (!empty($user)) {
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

    public function updateImage(Request $request) {
        $user = $this->getAuthUser();
        $user = $user->find(3);

        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1028'
        ]);

        if(!empty($user)) {
            $photo = $request->file('image');
            $path = 'public/images';
            $string = rand(22, 5033);
            if($photo != null) {
                $filename = $string . '__photo.' . $photo->getClientOriginalExtension();

                $img = $photo->storeAs($path, $filename);
                $user->image = $path . $filename;
            }
            $user->save;

            return response()->json([
                "message" => $photo
            ], 201);
        } else {
            return response()->json([
                "message" => "update profile failed"
            ], 400);
        }
    }

    public function uploadProfile(Request $request)
    {
        $user = $this->getAuthUser();
        $image  = $request->file('image');
        $userPath = 'profile_'.$user->name;

        if($user->image != null){
            $result = CloudinaryStorage::replace($user->image, $image->getRealPath(), $userPath);
        }else {
            $result = CloudinaryStorage::upload($image->getRealPath(), $userPath);
        }
        $user->image = $result;
        $user->save();

        return response()->json([
            "message" => "upload profile successfully"
        ], 201);
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
