<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Address;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;


class AddressController extends Controller
{

    public function addAddress(Request $request)
    {
        if (!$request->header('Authorization')) {
            return response()->json([
                'status' => false,
                'message' => 'Authorization header not found',
                'data' => null,
            ], 401);
        }
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        try {
            // Validate the token and get the authenticated user
            $user = JWTAuth::setToken($token)->authenticate();
        } catch (JWTException $e) {
            // If an exception occurs, return a 401 Unauthorized response
            return response()->json([
                'status' => false,
                'message' => 'Not authorized',
                'data' => null,
            ], 401);
        }

        if($user->address_id !== 'null'){
            return response()->json([
                'status' => false,
                'message' => 'Your address has been added before',
            ]);
        }

        $address = new Address($request->all());
        $address->save();
        $user->address_id = $address->id;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Your address has been added successfully',
            'data' => [
                'address' => $address
            ],
        ]);
    }













    public function getAddress(Request $request)
    {

        if (!$request->header('Authorization')) {
            return response()->json([
                'status' => false,
                'message' => 'Authorization header not found',
                'data' => null,
            ], 401);
        }
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        try {
            // Validate the token and get the authenticated user
            $user = JWTAuth::setToken($token)->authenticate();
        } catch (JWTException $e) {
            // If an exception occurs, return a 401 Unauthorized response
            return response()->json([
                'status' => false,
                'message' => 'Not authorized',
                'data' => null,
            ], 401);
        }


        $address = Address::find($user->address_id);

        if (!$address) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => null,
            'data' => [
                'address' => $address
            ],
        ]);
    }










    public function updateAddress(Request $request)
    {
        
        if (!$request->header('Authorization')) {
            return response()->json([
                'status' => false,
                'message' => 'Authorization header not found',
                'data' => null,
            ], 401);
        }
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        try {
            // Validate the token and get the authenticated user
            $user = JWTAuth::setToken($token)->authenticate();
        } catch (JWTException $e) {
            // If an exception occurs, return a 401 Unauthorized response
            return response()->json([
                'status' => false,
                'message' => 'Not authorized',
                'data' => null,
            ], 401);
        }

        
        $address = Address::find($user->address_id);
        // $user = JWTAuth::parseToken()->authenticate();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'details' => 'nullable|string|max:1000',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'notes' => 'nullable|string|max:1000',
            'updated_at'=>'nullable|timestamps',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
                'data' => null,
            ], 400);
        }


        if (!$address) {
            return response()->json(['error' => 'Address not found'], 404);
        }
        
    if ($request->filled('name')) {
        $address->name = $request->input('name');
    }
    if ($request->filled('city')) {
        $address->city = $request->input('city');
    }
    if ($request->filled('region')) {
        $address->region = $request->input('region');
    }
    if ($request->filled('details')) {
        $address->details = $request->input('details');
    }
    if ($request->filled('latitude')) {
        $address->latitude = $request->input('latitude');
    }
    if ($request->filled('longitude')) {
        $address->longitude = $request->input('longitude');
    }
    if ($request->filled('notes')) {
        $address->notes = $request->input('notes');
    }

        $address->save();

        return response()->json([
            'status' => true,
            'message' => 'Your address has been updated successfully',
            'data' => [
                'address' => $address
            ],
        ]);
    }









    public function deleteAddress(Request $request)
    {

        $token = str_replace('Bearer ', '', $request->header('Authorization'));

        if (!$request->header('Authorization')) {
            return response()->json([
                'status' => false,
                'message' => 'Authorization header not found',
                'data' => null,
            ], 401);
        }

        try {
            // Validate the token and get the authenticated user
            $user = JWTAuth::setToken($token)->authenticate();
        } catch (JWTException $e) {
            // If an exception occurs, return a 401 Unauthorized response
            return response()->json([
                'status' => false,
                'message' => 'Not authorized',
                'data' => null,
            ], 401);
        }
        // $user = JWTAuth::parseToken()->authenticate();
        $address = Address::find($user->address_id);

        if (!$address) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        $user->address_id = null;
        $user->save();
        $address->delete();

        return response()->json([
            'status' => true,
            'message' => 'Your address has been deleted',
            'data' => [
                null,
            ],
        ]);
    }
}
