<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
    {
        // if (!$request->header('Authorization')) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Authorization header not found',
        //         'data' => null,
        //     ], 401);
        // }
        // $token = str_replace('Bearer ', '', $request->header('Authorization'));
        // try {
        //     // Validate the token and get the authenticated user
        //     $user = JWTAuth::setToken($token)->authenticate();
        // } catch (JWTException $e) {
        //     // If an exception occurs, return a 401 Unauthorized response
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Not authorized',
        //         'data' => null,
        //     ], 401);
        // }

        if($user->role !== 'admin'){
            return response()->json([
                'status' => false,
                'message' => "You don't have admin permissions",
                'data' => null,
            ], 401);
        }

        $category = new Category($request->all());
        $category->save();
<<<<<<< Updated upstream
        $category = Category::paginate(10);
        $data = [
            'current_page' => $category->currentPage(),
            'data' => $category->map(function ($category) {
                return [
                    'id' => $category->id,
                    'type' => $category->type,
                    'value' => $category->value,
                    'image' => $category->image_url,
                ];
            }),
            'first_page_url' => $category->url(1),
            'from' => $category->firstItem(),
            'last_page' => $category->lastPage(),
            'last_page_url' => $category->url($category->lastPage()),
            'next_page_url' => $category->nextPageUrl(),
            'path' => $category->url($category->currentPage()),
            'per_page' => $category->perPage(),
            'prev_page_url' => $category->previousPageUrl(),
            'to' => $category->lastItem(),
            'total' => $category->total(),
        ];
=======
        $category->id;
        // $user->save();
>>>>>>> Stashed changes

        return response()->json([
            'status' => true,
            'message' => 'Your category has been added successfully',
            'data' => $data,
        ]);
    }

    public function getCategory()
    {
<<<<<<< Updated upstream
=======

        // if (!$request->header('Authorization')) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Authorization header not found',
        //         'data' => null,
        //     ], 401);
        // }
        // $token = str_replace('Bearer ', '', $request->header('Authorization'));
        // try {
        //     // Validate the token and get the authenticated user
        //     $user = JWTAuth::setToken($token)->authenticate();
        // } catch (JWTException $e) {
        //     // If an exception occurs, return a 401 Unauthorized response
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Not authorized',
        //         'data' => null,
        //     ], 401);
        // }


>>>>>>> Stashed changes
        $category = Category::get();
        
        if (!$category) {
            return response()->json(['error' => 'category not found'], 404);
        }
        $category = Category::paginate(10);
        $data = [
            'current_page' => $category->currentPage(),
            'data' => $category->map(function ($category) {
                return [
                    'id' => $category->id,
                    'type' => $category->type,
                    'value' => $category->value,
                    'image' => $category->image_url,
                ];
            }),
            'first_page_url' => $category->url(1),
            'from' => $category->firstItem(),
            'last_page' => $category->lastPage(),
            'last_page_url' => $category->url($category->lastPage()),
            'next_page_url' => $category->nextPageUrl(),
            'path' => $category->url($category->currentPage()),
            'per_page' => $category->perPage(),
            'prev_page_url' => $category->previousPageUrl(),
            'to' => $category->lastItem(),
            'total' => $category->total(),
        ];
        return response()->json([
            'status' => true,
            'message' => 'this is all Categories ',
<<<<<<< Updated upstream
            'data' => $data,
=======
            'data' => [
                'current_page'=>1,
                [
                'categories' => $category
                ],
            ],
>>>>>>> Stashed changes
        ]);
    }

    public function updateCategory(Request $request)
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

<<<<<<< Updated upstream
        if($user->role !== 'admin'){
            return response()->json([
                'status' => false,
                'message' => "You don't have admin permissions",
                'data' => null,
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:100',
            'image' => 'nullable|string|max:100',
=======

        // $category = Category::get('id');
        $category = Category::find();
        // $user = JWTAuth::parseToken()->authenticate();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:100',
            'product_name' => 'nullable|string|max:100',
            'product_image' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric',
            'updated_at' => 'nullable|timestamps',

>>>>>>> Stashed changes
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
                'data' => null,
            ], 400);
        }


        $category = Category::get();
        
        if ($request->filled('name')) {
            $category->name = $request->input('name');
        }
<<<<<<< Updated upstream
        if ($request->filled('image')) {
            $category->product_name = $request->input('image');
=======
        if ($request->filled('product_name')) {
            $category->product_name = $request->input('product_name');
        }
        if ($request->filled('product_image')) {
            $category->product_image = $request->input('product_image');
        }
        if ($request->filled('amount')) {
            $category->amount = $request->input('amount');
        }
        if ($request->filled('updated_at')) {
            $category->updated_at = $request->input('updated_at');
>>>>>>> Stashed changes
        }



        $category->save();
        $category = Category::paginate(10);
        $data = [
            // 'current_page' => $category->currentPage(),
            'data' => $category->map(function ($category) {
                return [
                    'id' => $category->id,
                    'type' => $category->type,
                    'value' => $category->value,
                    'image' => $category->image_url,
                ];
            }),
            'first_page_url' => $category->url(1),
            'from' => $category->firstItem(),
            'last_page' => $category->lastPage(),
            'last_page_url' => $category->url($category->lastPage()),
            'next_page_url' => $category->nextPageUrl(),
            'path' => $category->url($category->currentPage()),
            'per_page' => $category->perPage(),
            'prev_page_url' => $category->previousPageUrl(),
            'to' => $category->lastItem(),
            'total' => $category->total(),
        ];

        return response()->json([
            'status' => true,
            'message' => 'Your category has been updated successfully',
            'data' => $data,
        ]);
    }

}
