<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Notifications\EmailVerificationNotification;

class UserController extends Controller
{



    // set the login api with (post)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status' => false,
                'message' => 'This credentials does not meet any of our records, please make sure you have entered the right credentials',
                'data' => null,
            ], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => false, 'message' => 'Could not create token'], 500);
        }
        $user = auth()->user();
        return response()->json([
        'status' => true,
        'message' => 'Login done successfully',
        'data' => [
            'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'image' => $user->image ?: 'https://student.valuxapps.com/storage/assets/defaults/user.jpg',
                'points' => $user->points,
                'credit' => $user->credit,
                'token' => $token,
        ]
    ]);
    }











// set the register api (post)

    public function register(Request $request)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Set default image if not provided
        $image = $request->input('image', 'https://student.valuxapps.com/storage/assets/defaults/user.jpg');

        // Create a new user with input data and default image
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'image' => $image,
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status' => true, 'message' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => false, 'message' => 'Could not create token'], 500);
        }

        //send notification
        // $user->notify(new EmailVerificationNotification());
        // Return success response with user data
        
        return response()->json([
            'status' => true,
            'message' => 'Registration done successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'image' => $user->image ?: 'https://student.valuxapps.com/storage/assets/defaults/user.jpg',
                'token' => $token,
            ]
        ], 201);
    }















// set the logout api with (post) 

    public function logout(Request $request)
    {
        
        $token = $request->bearerToken();
        
        try {

            JWTAuth::parseToken($token)->invalidate();
            return response()->json([
                'status'=>true,
                'message' => 'Logout done successfully',
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
            'status'=>false,
            'message' => 'Not Authorized',
            ], 500);
        }
    }















// get the profile api data


    public function getProfile(Request $request)
    {
        if (!$request->header('Authorization')) {
            return response()->json([
                'status' => false,
                'message' => 'Authorization header not found',
                'data' => null,
            ], 401);
        }
    
        // Extract the token from the Authorization header
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
    
        // Return the user's profile information
        return response()->json([
            'status' => true,
            'message' => null,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'image' => $user->image ?: 'https://student.valuxapps.com/storage/assets/defaults/user.jpg',
                'points' => $user->points,
                'credit' => $user->credit,
                'token' => $token,
            ]
        ]);
    }
















    public function updateProfile(Request $request)
    {
        // Get the authenticated user
        
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        // $user = JWTAuth::parseToken()->authenticate();
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
        // $address = Address::find($user->address_id);
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
                'data' => null,
            ], 400);
        }
        
        // Update the user's information
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_url = Storage::putFile('public/images', $image);
            $user->image = $image_url;
        }
        
        $user->save();

        // Return the updated user profile information
        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'image' => $user->image ?: 'https://student.valuxapps.com/storage/assets/defaults/user.jpg',
                'points' => $user->points,
                'credit' => $user->credit,
                'token' => $token,
            ]
        ]);
    }













    public function verifyEmail(Request $request)
{
    if (!$request->header('Authorization')) {
        return response()->json([
            'status' => false,
            'message' => 'Authorization header not found',
            'data' => null,
        ], 401);
    }

    // Extract the token from the Authorization header
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

    if ($user) {
        if ($user->email_verified_at === null) {
            return response()->json([
                'status' => false,
                'message' => 'Email not verified',
                'email_verified_at' => null,
                'data' => null
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Email verified',
                'email_verified_at' => $user->email_verified_at,
                'data' => $user
            ]);
        }
    } else {
        return response()->json([
            'status' => false,
            'message' => 'Email not found',
            'email_verified_at' => null,
            'data' => null
        ]);
    }
}


public function changePassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'current_password' => 'required',
        'new_password' => 'required|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    if (!$request->header('Authorization')) {
        return response()->json([
            'status' => false,
            'message' => 'Authorization header not found',
            'data' => null,
        ], 401);
    }

    // Extract the token from the Authorization header
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

    $credentials = [
        'email' => $user->email,
        'password' => $request->input('current_password'),
    ];

    if (!auth()->attempt($credentials)) {
        return response()->json([
            'status' => false,
            'message' => 'This credentials does not meet any of our records, please make sure you have entered the right credentials',
            'data' => null,
        ], 401);
    }

    $user->password = bcrypt($request->input('new_password'));
    $user->save();

    return response()->json([
        'status' => true,
        'message' => 'Updated Successfully',
        'data' => ['email' => $user->email],
    ], 200);
}

}

