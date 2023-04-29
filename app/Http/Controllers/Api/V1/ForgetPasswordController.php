<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Http\Requests\Auth\ForgetPasswordRequest;


class ForgetPasswordController extends Controller
{
    public function forgetPassword(ForgetPasswordRequest $request){
        $input = $request->only('email');
        $user = User::where('email',$input)->first();
        $user->notify(new ResetPasswordNotification());
        return response()->json(['status' => true,'message' => 'We Send a reset code to your email'],200);
    }
}
