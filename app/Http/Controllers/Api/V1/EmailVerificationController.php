<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    private $otp;
    public function __construct()
    {
        $this->otp = new Otp;
    }

    public function verifiyCode(EmailVerificationRequest $request)
    {


        $otp2 = $this->otp->validate($request->email,$request->code);
        if(!$otp2->status){
            return response()->json(['status' => false,'message' => 'No data found','data' => null],401);
        }

        $user = User::where('email', $request->email)->first();
        
        $user->email_verified_at = now();
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Your email is Verified Now',
        ], 200);
    }
}
