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
            "price" => "required",
            "duration" => "required",
            "category" => "required"
        ]);

        $service = new Service;
        $service->name = $request->name;
        $service->price = $request->price;
        $service->duration = $request->duration;
        $service->category = $request->category;
        $service->vendor()->associate($vendor)->save();

        return response()->json([
            "message" => "Service create successfully"
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $vendor = $this->authVendor();
        $service = Service::where('id', $id)->first();

        if (!empty($service)) {
            $service->name = is_null($request->name) ? $service->name : $request->name;
            $service->price = is_null($request->price) ? $service->price : $request->price;
            $service->duration = is_null($request->duration) ? $service->duration : $request->duration;
            $service->category = is_null($request->category) ? $service->category : $request->category;
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

    public function destroy(Request $request, $id)
    {
        $vendor = $this->authVendor();
        $service = Service::where('id', $id)->first();

        if (!empty($service)) {
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

    public function index() {
        $vendor = $this->authVendor();
        $service = Service::where('vendor_id', $vendor->id)->get();

        return response()->json([
            "data" => $service
        ], 201);
    }

    public function show(Request $request, $id) {
        $vendor = $this->authVendor();
        $service = Service::where('id', $id)->first();

        return response()->json([
            "data" => $service
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
