<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\EmailVerification;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $formFields = $request->validate([
            "name" => ["required", "min:3"],
            "email" => ["required", "email", Rule::unique("users", "email")],
            "password" => ["required", "confirmed", "min:6"]
        ]);

        // Hash Password
        $formFields["password"] = bcrypt($formFields["password"]);

        // Create User
        $user = User::create($formFields);
        if ($user) {
            // Send verification email
            Mail::to($user->email)->send(new EmailVerification($user));
            return response()->json([
                "type" => "success",
                "message" => "User created succesfully. We sent you an email. Please activate your account.."
            ], 201);
        }
    }
    public function login(Request $request)
    {

        $userCheck = User::where("email", $request->email)->where("status",1)->first();
        if (empty($userCheck)) {
            return response()->json([
                "type" => "error",
                "message" => "Please activate your account.",
            ], 401);
            exit;
        }

        $formFields = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required", "min:6"]
        ]);

        if (auth()->attempt($formFields)) {
            $user = auth()->user();
            $access_token = $user->createToken("LaraPass");

            return response()->json([
                "type" => "success",
                "message" => "You have successfully login. Use your access token to authorize yourself.",
                "token" => $access_token
            ], 200);
        } else {
            return response()->json([
                "type" => "error",
                "message" => "The data you have entered is incorrect.",
            ], 401);
        }
    }
    public function logout()
    {
        $user_id = auth("api")->user()->id;

        $delete = DB::table("oauth_access_tokens")->where("user_id", $user_id)->delete();

        if ($delete) {
            return response()->json([
                "type" => "success",
                "message" => "Logout successfull.",
            ], 200);
        } else {
            return response()->json([
                "type" => "error",
                "message" => "Unexpected Error.",
            ], 500);
        }
    }
}
