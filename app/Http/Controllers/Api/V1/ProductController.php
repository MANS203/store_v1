<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;


class ProductController extends Controller
{
    public function addProduct(Request $request)
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

        if($user->role !== 'admin'){
            return response()->json([
                'status' => false,
                'message' => "You don't have admin permissions",
                'data' => null,
            ], 401);
        }
        
        $product = new Product($request->all());
        $product->save();
        $category= Category::get();
        // $user->category_id = $category->product_id= $product->id;
        $category->product_id = $product->id;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Your product has been added successfully',
            'data' => [
                'product' => $product
            ],
        ]);
    }


    /***************************************************************** */

    public function getProduct()
    {
        $product = Product::get();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => null,
            'data' => [
                'product' => $product
            ],
        ]);
    }


    /*********************************** */


    public function updateProduct(Request $request)
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

        if($user->role !== 'admin'){
            return response()->json([
                'status' => false,
                'message' => "You don't have admin permissions",
                'data' => null,
            ], 401);
        }

        $category= Category::get();
        $product = Product::find($category->product_id);
        // $user = JWTAuth::parseToken()->authenticate();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:100',
            'type' => 'nullable|string|max:100',
            'price' => 'nullable|numeric',
            'updated_at' => 'nullable|timestamps',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
                'data' => null,
            ], 400);
        }



        if ($request->filled('name')) {
            $product->name = $request->input('name');
        }
        if ($request->filled('type')) {
            $product->type = $request->input('type');
        }
        if ($request->filled('price')) {
            $product->price = $request->input('price');
        }
        if ($request->filled('updated_at')) {
            $product->updated_at = $request->input('updated_at');
        }



        $product->save();

        return response()->json([
            'status' => true,
            'message' => 'Your product has been updated successfully',
            'data' => [
                'product' => $product
            ],
        ]);
    }









    public function deleteProduct(Request $request)
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

        if($user->role !== 'admin'){
            return response()->json([
                'status' => false,
                'message' => "You don't have admin permissions",
                'data' => null,
            ], 401);
        }
        
        // $user = JWTAuth::parseToken()->authenticate();
        $category=Category::get();
        $product = Product::find($category->product_id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $category->product_id = null;
        $category->save();
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Your product has been deleted',
            'data' => [
                null,
            ],
        ]);
    }
    // public function searchProduct(Request $request)
    // {

    //     // if (!$request->header('Authorization')) {
    //     //     return response()->json([
    //     //         'status' => false,
    //     //         'message' => 'Authorization header not found',
    //     //         'data' => null,
    //     //     ], 401);
    //     // }
    //     // $token = str_replace('Bearer ', '', $request->header('Authorization'));
    //     // try {
    //     //     // Validate the token and get the authenticated user
    //     //     $user = JWTAuth::setToken($token)->authenticate();
    //     // } catch (JWTException $e) {
    //     //     // If an exception occurs, return a 401 Unauthorized response
    //     //     return response()->json([
    //     //         'status' => false,
    //     //         'message' => 'Not authorized',
    //     //         'data' => null,
    //     //     ], 401);
    //     // }




    //     // $product = Product::where('name',"%{$product}%")->orWhere('type',"%{$product}%")->get();
    //     // $name=($request->filled('name'));
    //     // $description=($request->filled('description'));

    //     // $product= Product::search('name', '%'.$name.'%')->orwhereTranslationLike('description', '%'.$description .'%')->get();


    //     // if ($product !=Product ) {
    //     //     return response()->json(['error' => 'Product not found'], 404);
    //     // }
    //     // $description = $request->input('description');
    //     $search = $request->input('name_sreach');



    //     $product = Product::where('name', 'LIKE', "%$search%")->get();

    //     // if($search!=$product){
    //     //     return response()->json(['error' => 'Product not found'], 404);
    //     // }


    //     return response()->json([
    //         'status' => true,
    //         'message' => null,
    //         'data' => [
    //             'product' => $product
    //         ],
    //     ]);


}
