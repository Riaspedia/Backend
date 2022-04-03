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
        // ada 2 cara.. dibatkan function baru atau dilooping disini..
        // mau pake yg mana? looping mi//tgg browsingka looping php kulupai mode
        // kopyormi kepalaku wkwkkw.. simpanmi dlu benya lumayan panjang... fenya mo dlu tinggal di uncomment
        // atau kabari tim be nnti cek digithub ini untuk multiple file benya lumayan banyak diganti
        // coding fenya dimana?
        // https://github.com/zulfauzi92/otakKanan_backend/blob/main/app/Http/Controllers/MyOfficeController.php
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
