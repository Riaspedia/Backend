<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function store(Request $request)
    {
        $user = $this->getAuthUser();

        $request->validate([
            "name" => "required",
            "phone" => "required",
        ]);

        $vendor = new Vendor;
        $vendor->name = $request->name;
        $vendor->phone =  $request->phone;
        $vendor->description = $request->description;
        $vendor->email = $request->email;
        $vendor->address = $request->address;
        $vendor->city = $request->city;
        $vendor->latitude = $request->latitude;
        $vendor->longitude = $request->longitude;

        $vendor->user()->associate($user)->save();

        return response()->json([
            "message" => "create vendor successfully"
        ], 201);
    }

    public function update(Request $request)
    {
        $user = $this->getAuthUser();
        $vendor = $user->vendor()->get();

        if (!empty($vendor)) {
            $vendor->name = is_null($request->name) ? $vendor->name : $request->name;
            $vendor->phone = is_null($request->phone) ? $vendor->phone : $request->phone;
            $vendor->description = is_null($request->description) ? $vendor->description : $request->description;
            $vendor->email = is_null($request->email) ? $vendor->email : $request->email;
            $vendor->address = is_null($request->address) ? $vendor->address : $request->address;
            $vendor->city = is_null($request->city) ? $vendor->city : $request->city;
            $vendor->latitude = is_null($request->latitude) ? $vendor->latitude : $request->latitude;
            $vendor->longitude = is_null($request->longitude) ? $vendor->longitude : $request->longitude;
            $vendor->save();

            return response()->json([
                "message" => "vendor updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "vendor not found"
            ], 404);
        }
    }

    public function destroy (Request $request)
    {
        $user = $this->getAuthUser();
        $vendor = $user->vendor()->get();
        if (!empty($vendor)) {
            $vendor->user()->detach();
            $vendor->delete();
            return response()->json([
                "message" => "records deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "vendor not found"
            ], 404);
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
