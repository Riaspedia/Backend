<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function store(Request $request)
    {
        $vendor = $this->authVendor();
        $image  = $request->file('image');
        $result = CloudinaryStorage::upload($image->getRealPath(), $image->getClientOriginalName());
        $gallery = new Gallery;
        $gallery->image = $result;
        $gallery->vendor()->associate($vendor)->save();

        return response()->json([
            "message" => "image upload successfully"
        ], 201);
    }

    public function update(Request $request)
    {
        $file   = $request->file('image');
        $image = Gallery::all()->find($request->imageId);
        $result = CloudinaryStorage::replace($image->image, $file->getRealPath(), $file->getClientOriginalName());
        $image->update(['image' => $result]);
        return response()->json([
            "message" => "image update successfully"
        ], 201);
    }

    public function destroy(Request $request)
    {
        $image = Gallery::all()->find($request->imageId);
        CloudinaryStorage::delete($image->image);
        $image->delete();
        return response()->json([
            "message" => "image delete successfully"
        ], 201);
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
