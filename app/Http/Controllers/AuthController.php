<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use Illuminate\support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\TokenRepository;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Type;

class AuthController extends Controller
{
    // public function add_role(Request $request)
    // {
    //     try {
    //         $role = Role::create([
    //             'name' => $request->role,
    //             'guard_name' => 'api',
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Role created successfully',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage(),
    //         ], 400);
    //     }
    // }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string',
            'phone' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
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
            $existingUser->address = $request->address;
            $existingUser->user_img = $request->user_img;
            $existingUser->save();

            return response()->json(['message' => 'Account activated. You can now log in'], 200);
        }

        try {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'dob' => $request->dob,
                'type' => $request->type,
                'address' => $request->address,
                'status' => 1,
                'user_img' => $request->user_img,
                'device_token' => 'eHTHTrzYT_qw786ywVvida:APA91bEMNkQBG5fu0Fom2s17_mqygKhTmwDVk9lsHPYlUDPCD-29AXBn2JMG4yDaxxI3owsD8BBBx_maLQF4fPko7yRz8HNUxcmtgLemkfRqgPl5J-Ols7GMDmIb1qCHQVjQxHQbt3h2',
            ]);
            $user->save();
            return response()->json(['message' => 'User has been registered '], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function googleLogin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'google_id' => 'required',
        ]);
        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->google_id = $request->input('google_id');
            $user->save();
        }
        return response()->json(['message' => 'User created successfully']);
    }
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            if ($user->status == 0) {
                return response()->json(['error' => 'Unauthorized: Account deleted'], 401);
            }
    
            if ($user->type === 'admin') {
                return response()->json(['error' => 'Unauthorized: Admin access denied'], 401);
            }
    
            $token = $user->createToken('UserApp')->accessToken;
            return response()->json(['message' => 'User Profile', 'token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    
    
    

    public function profile()
    {
        try {
            $user = auth()->user();
            if (!$user) {
                throw new \Exception('User not found.');
            }
            return response()->json([
                'success' => true,
                'message' => 'Data fetched successfully.',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }


    public function user_update(Request $request)
    {
        try {
            $input = $request->all();
            $rules = array(
                'id' => "required",
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ], 400);
            } else {
                User::Where('id', $request->id)->update($input);
                return response()->json(['success' => true, 'messsage' => 'User update successfully'], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'messsage' => $e->getMessage()], 403);
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

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image:jpeg,png,jpg,gif,svg'
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
        $uploadFolder = 'images';
        $image = $request->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $uploadedImageResponse = array(
            "image_name" => basename($image_uploaded_path),
            "image_url" => env('APP_URL') . "/public/storage/images/" . basename($image_uploaded_path),
            "mime" => $image->getClientMimeType()
        );
        return response()->json(['message' => 'File Uploaded Successfully', 'data' => $uploadedImageResponse], 200);
    }

    public function react_image_upload(Request $request)
    {
        $image = $request->image;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $mytime = Carbon::now();
        $imageName = $mytime->toDateTimeString() . '.png';
        $imName = str_replace('_', ' ', $imageName);
        $cImage = base64_decode($image);

        Storage::disk('local')->put($imName, base64_decode($image));

        $uploadedImageResponse = array(
            "image_name" => basename($imName),
            "image_url" => env('APP_URL') . "/public/storage/" . basename($imName),
        );
        return response()->json(['message' => 'File Uploaded Successfully', 'data' => $uploadedImageResponse], 200);
    }
}