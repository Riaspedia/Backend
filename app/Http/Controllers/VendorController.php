<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Day;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
        $vendor->category = $request->category;

        $vendor->user()->associate($user)->save();

        return response()->json([
            "message" => "create vendor successfully",
            "data" => $vendor->id
        ], 201);
    }

    public function uploadProfile(Request $request)
    {
        $user = $this->getAuthUser();
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1028'
        ]);
        $image  = $request->file('image');
        $vendor = $user->vendor;
        $userPath = 'profile_'.$vendor->name;

        if($vendor->image != null){
            $result = CloudinaryStorage::replace($vendor->image, $image->getRealPath(), $userPath);
        }else {
            $result = CloudinaryStorage::upload($image->getRealPath(), $userPath);
        }
        $vendor->image = $result;
        $vendor->save();


        return response()->json([
            "message" => "upload profile successfully"
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = $this->getAuthUser();
        $vendor = Vendor::where('id', $id)->first();


        if (!empty($vendor)) {
            $vendor->name = is_null($request->name) ? $vendor->name : $request->name;
            $vendor->phone = is_null($request->phone) ? $vendor->phone : $request->phone;
            $vendor->description = is_null($request->description) ? $vendor->description : $request->description;
            $vendor->email = is_null($request->email) ? $vendor->email : $request->email;
            $vendor->address = is_null($request->address) ? $vendor->address : $request->address;
            $vendor->city = is_null($request->city) ? $vendor->city : $request->city;
            $vendor->latitude = is_null($request->latitude) ? $vendor->latitude : $request->latitude;
            $vendor->longitude = is_null($request->longitude) ? $vendor->longitude : $request->longitude;
            $vendor->category = is_null($request->category) ? $vendor->category : $request->category;
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
//halaman yg mana adminnya? ini servernya belum dideploy atau masih local?masih local, controller untuk upload image admin dimana?
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

    public function index ()
    {
        setlocale(LC_ALL, 'IND');
        $currentDay = Carbon::now()->isoFormat('dddd');
        $vendor = Vendor::with('hours')->get();
        $day_id = Day::where('name', $currentDay)->first();


        return response()->json([
            "data" => $vendor,
            "day" => $day_id
        ], 201);
    }

    public function show (Request $request, $id)
    {
        setlocale(LC_ALL, 'IND');
        $currentDay = Carbon::now()->isoFormat('dddd');
        $vendor = Vendor::where('id', $id)->with('services')->with('reviews', 'reviews.user', 'reviews.services')->with(['hours' => function($q) {
            $q->orderBy('day_id');
        }])->first();
        $day_id = Day::where('name', $currentDay)->first();
        $days = Day::all();


        return response()->json([
            "data" => $vendor,
            "current_day" => $day_id,
            "days" => $days
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
