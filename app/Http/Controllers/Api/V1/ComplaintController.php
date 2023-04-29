<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ComplaintController extends Controller
{
    public function addComplaint(Request $request)
    {
        $complaint = new Complaint;
        $complaint->name = $request->name;
        $complaint->phone = $request->phone;
        $complaint->email = $request->email;
        $complaint->message = $request->message;
        $complaint->save();

        return response()->json(['success' => true, 'message' => 'Complaint submitted successfully.','data' => $complaint]);
    }
}
