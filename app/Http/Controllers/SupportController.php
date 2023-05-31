<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    public function create(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();

            $validator = Validator::make($request->all(), [
                'description' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $userId = $user->id;
            $input = $request->input('description');

            $support = new Support();
            $support->user_id = $userId;
            $support->description = $input;
            $support->save();

            return response()->json(['message' => 'Support request created successfully']);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
