<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Otps;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\CheckResetCodeRequest;

class ResetPasswordController extends Controller
{
    private $otp;
    
    public function __construct()
    {
        $this->otp = new Otp;
    }

    public function resetPassword(ResetPasswordRequest $request){

        $otp2 = $this->otp->validate($request->email,$request->code);

        if(!$otp2->status){
            return response()->json(['error' => $otp2],401);
        }

        $user = User::where('email',$request->email)->first();
        $user->update(['password' => bcrypt($request->password)]);

        return response()->json(['status' => true,'message' => 'password reseted successfully'],200);
    }

    public function checkResetCode(CheckResetCodeRequest $request){

        $otp = Otps::where('identifier', $request->email)->orderBy('id', 'desc')->first();
        $token = $otp ? $otp->token : null;

        if($token !== $request->code){
            return response()->json(['status' => false,'message' => "Invalid code"],401);
        }
        return response()->json(['status' => true,'message' => 'The code is correct'],200);
    }
}
