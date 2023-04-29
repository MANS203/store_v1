<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ContactsController;
use App\Http\Controllers\Api\V1\ComplaintController;
use App\Http\Controllers\Api\V1\ResetPasswordController;
use App\Http\Controllers\Api\V1\ForgetPasswordController;
use App\Http\Controllers\Api\V1\EmailVerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1/users')->group(function(){
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'getProfile']);
    Route::put('/update-profile', [UserController::class, 'updateProfile']);
    Route::post('/verify-email', [UserController::class, 'verifyEmail']);
    Route::post('/verify-code', [EmailVerificationController::class, 'verifiyCode']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
    Route::post('/forget-password', [ForgetPasswordController::class, 'forgetPassword']);
    Route::post('/reset', [ResetPasswordController::class, 'resetPassword']);
    Route::post('/check-reset-code', [ResetPasswordController::class, 'checkResetCode']);
});

Route::prefix('v1/address')->group(function(){

    Route::post('/add-address', [AddressController::class, 'addAddress']);
    Route::get('/get-address', [AddressController::class, 'getAddress']);
    Route::put('/update-address', [AddressController::class, 'updateAddress']);
    Route::delete('/delete-address', [AddressController::class, 'deleteAddress']);
});



Route::prefix('v1/products')->group(function(){

    Route::post('/add-product',[ProductController::class,'addProduct']);
    Route::get('/get-product',[ProductController::class,'getProduct']);
    Route::put('/update-product',[ProductController::class,'updateProduct']);
    Route::delete('/delete-product',[ProductController::class,'deleteProduct']);
    // Route::post('/sreach_product',[ProductController::class,'searchProduct']);

});

Route::prefix('v1/categories')->group(function(){

    Route::post('/add-category',[CategoryController::class,'addCategory']);
    Route::get('/get-category',[CategoryController::class,'getCategory']);
    Route::put('/update-category',[CategoryController::class,'updateCategory']);
});

Route::prefix('v1/contacts')->group(function(){
    Route::get('get-contacts',[ContactsController::class,'getContacts']);
});


Route::prefix('v1/complaints')->group(function(){
    Route::post('add-complaint',[ComplaintController::class,'addComplaint']);
});
