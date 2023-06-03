<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Otp;
use App\Models\User;
// use App\Models\Role;

use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use Illuminate\support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\TokenRepository;
use Spatie\Permission\Models\Role;



class AuthController extends Controller
{
    public function add_role(Request $request)
    {
        try {
            $role = Role::create([
                'name' => $request->role,
                'guard_name' => 'api',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
            'role_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser && $existingUser->status == 0) {
            $existingUser->status = 1;
            $existingUser->password = Hash::make($request->password);
            $existingUser->name = $request->name;
            $existingUser->phone = $request->phone;
            $existingUser->dob = $request->dob;
            $existingUser->from = $request->from;
            $existingUser->save();

            return response()->json(['message' => 'Account activated. You can now log in'], 200);
        }

        try {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'phone' => $request->phone,
                'dob' => $request->dob,
                'from' => $request->from,
                'status' => 1,
            ]);

            if ($request->hasFile('user_img')) {
                $image = $request->file('user_img');
                $path = $image->store('user_img', 'public');
                $user->user_img = $path;
            }

            $user->save();
            $role = Role::where(['id'=>$request->role_id,'guard_name'=>'api'])->first();
            if ($role) {
                $user->assignRole($role);
            }

            if ($role->id == 1) {
                return response()->json(['message' => 'User has been registered as an admin'], 200);
            } else {
                return response()->json(['message' => 'User has been registered as a regular user'], 200);
            }
            return response()->json(['message' => 'User has been registered'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function login()
    {
        $credentials = request(['email', 'password']);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->role_id == 1) {
            return response()->json(['error' => 'Unauthorized: Admin login not allowed'], 401);
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->status == 0) {
                return response()->json(['error' => 'Unauthorized: Account deleted'], 401);
            }

            $token = $user->createToken('MyApp')->accessToken;

            if ($user->role_id == 1) {
                return response()->json(['message' => 'Admin logged in', 'token' => $token], 200);
            } else {
                return response()->json(['message' => 'User logged in', 'token' => $token], 200);
            }
        } else {
            return response()->json(['error' => 'Unauthorized: Invalid credentials'], 401);
        }
    }



public function profile()
{
    try {
        $user = Auth::user();
        $roleName = getRole($user->role_id);
        

        if ($user->status == 0) {
            return response()->json(['error' => 'Unauthorized: Account deleted'], 401);
        }

        return response()->json([
            'user' => $user,
            'role' => $roleName
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 401);
    }
}



    public function logout(Request $request)
    {
        $access_token = auth()->user()->token();
        $tokenRepository = app(TokenRepository::class);
        $tokenRepository->revokeAccessToken($access_token->id);

        return response()->json([
            'message' => 'User logout successfully.',
            'status' => true,
        ]);
    }

    public function changePass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 400,
                "message" => $validator->errors()->first(),
                "data" => []
            ]);
        }

        $user = Auth::guard('api')->user();

        if (!Hash::check($request->input('old_password'), $user->password)) {
            return response()->json([
                "status" => 400,
                "message" => "Check your old password.",
                "data" => []
            ]);
        }

        if (Hash::check($request->input('new_password'), $user->password)) {
            return response()->json([
                "status" => 400,
                "message" => "Please enter a password that is not similar to the current password.",
                "data" => []
            ]);
        }

        try {
            $user->update(['password' => Hash::make($request->input('new_password'))]);
            return response()->json([
                "status" => 200,
                "message" => "Password updated successfully.",
                "data" => []
            ]);
        } catch (\Exception $ex) {
            $msg = isset($ex->errorInfo[2]) ? $ex->errorInfo[2] : $ex->getMessage();
            return response()->json([
                "status" => 400,
                "message" => $msg,
                "data" => []
            ]);
        }
    }


    function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        $otp = rand(pow(10, 4 - 1), pow(10, 4) - 1);
        $data = array(
            'otp' => $otp,
            'message' => 'Your Otp Code For Forget Password'
        );

        try {
            $existingOtpRecord = Otp::where('email', $request->email)->first();

            if ($existingOtpRecord) {
                $existingOtpRecord->otp = $otp;
                $existingOtpRecord->save();
            } else {
                $otpRecord = new Otp();
                $otpRecord->email = $request->email;
                $otpRecord->otp = $otp;
                $otpRecord->save();
            }

            Mail::to($request->email)->send(new SendMail($data));

            return response()->json(['status' => 'success', 'message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function forgetPass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
            'email' => 'required',
            'password' => 'required|min:6|max:100',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        try {
            $otpRecord = Otp::where('email', $request->email)->first();
            if (!$otpRecord) {
                return response()->json(['message' => 'User not found'], 404);
            }

            if ($otpRecord->otp != $request->otp) {
                return response()->json(['message' => 'OTP does not match'], 400);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $user->update([
                'password' => Hash::make($request->password)
            ]);
            $otpRecord->otp = $request->otp;
            $otpRecord->update();

            return response()->json(['message' => 'Forget Password Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function delete(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->status == 1) {
            $user->status = 0;
            $user->save();

            return response()->json(['message' => 'Account deleted successfully', 'status' => $user->status]);
        } else {
            $user->status = 1;
            $user->save();

            return response()->json(['message' => 'User account registration enabled', 'status' => $user->status]);
        }
    }



}