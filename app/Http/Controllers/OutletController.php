<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlet;
use Illuminate\Support\Facades\Validator;

class OutletController extends Controller
{

    public function index() 
    {
        $outlet = Outlet::all();

        return response()->json([
            "data" => $outlet
        ], 201);
    }

    public function show($id) 
    {
        $outlet = Outlet::find($id);

        return response()->json([
            "data" => $outlet
        ], 201);
    }

    public function store(Request $request) 
    {
        $user = $this->getAuthUser();
        
        $request->validate([
            "title" => "required",
            "description" => "required",
            "price" => "required",
            "address" => "required",
        ]);

        $outlet = Outlet::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'address' => $request->address
        ]);

        $outlet->save();

        return response()->json([
            "message" => "create vendor successfully"
        ], 201);
    }

    public function update(Request $request, $id) 
    {
        $user = $this->getAuthUser();
        $outlet = Outlet::find($id);

        if(!empty($outlet)) {
            $outlet->title = $request->title;
            $outlet->description = $request->description;
            $outlet->price = $request->price;
            $outlet->address = $request->address;

            $outlet->save();

            return response()->json([
                "message" => "Update outlet successfully"
            ], 201);
        } else {
            return response()->json([
                "message" => "update outlet failed"
            ], 400);
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