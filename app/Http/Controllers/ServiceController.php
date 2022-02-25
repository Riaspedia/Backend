<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function store(Request $request)
    {
        $vendor = $this->authVendor();

        $request->validate([
            "name" => "required",
            "price" => ['required', 'regex:^(?:[1-9]\d+|\d)(?:\,\d\d)?$'],
        ]);

        $service = Service::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        $service->vendor()->attach($vendor);

        return response()->json([
            "message" => "Service create successfully"
        ], 201);
    }

    public function update(Request $request)
    {
        $vendor = $this->authVendor();
        $service = $vendor->services()->find($request->id);

        if (!empty($service)) {
            $service->name = is_null($request->name) ? $service->name : $request->name;
            $service->price = is_null($request->price) ? $service->price : $request->price;
            $service->save();

            return response()->json([
                "message" => "Update service successfully"
            ], 201);
        } else {
            return response()->json([
                "message" => "service not found"
            ], 404);
        }
    }

    public function destroy(Request $request)
    {
        $vendor = $this->authVendor();
        $service = $vendor->services()->find($request->id);

        if (!empty($service)) {
            $service->vendor()->detach();
            $service->delete();
            return response()->json([
                "message" => "service deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "service not found"
            ], 404);
        }

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
