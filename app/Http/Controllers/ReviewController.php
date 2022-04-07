<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Service;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $user = $this->getAuthUser();
        $vendor = Vendor::find($request->vendorId);

        $request->validate([
            "description" => "required",
            "score" => "required",
        ]);

        foreach ($request->servicesId as $service){
            $cekService = Service::find($service);
            if($cekService == null){
                return response()->json([
                    "message" => "service not found"
                ], 404);
            }
        }

        $review = new Review;
        $review->description = $request->description;
        $review->score = $request->score;
        $review->user()->associate($user);
        $review->vendor()->associate($vendor);
        $review->save();
        $review->services()->attach($request->servicesId);

        return response()->json([
            "message" => "review create successfully"
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
