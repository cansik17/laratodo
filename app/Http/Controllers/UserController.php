<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function create()
    {
        return view("users.register");
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            "name" => ["required", "min:3"],
            "email" => ["required", "email", Rule::unique("users", "email")],
            "password" => ["required", "confirmed", "min:6"]
        ]);

        // Hash Password
        $formFields["password"] = bcrypt($formFields["password"]);
        $formFields["email_verification_token"] = bin2hex(random_bytes(16));
        $formFields["status"] = 0;

        // Create User
        $user = User::create($formFields);

        //Login

        //auth()->login($user);

        // Send verification email


        Mail::to($user->email)->send(new EmailVerification($user));

        return redirect("/")->with("message", "User created successfuly.We sent you an email. Please activate your account.");
    }

    public function activateAccount(Request $request)
    {
        $email = $request->email;
        $token = $request->token;

        $user = User::where("email", $email)->where("email_verification_token", $token)->where("status", 0)->first();

        if ($user) {
            User::where("email", $email)->update(["status" => 1, "email_verified_at" => now()]);
            return redirect("/")->with("message", "Your account activated successfully.");
        } else {
            return redirect("/")->with("message", "Error.");
        }
    }


    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        echo json_encode([
            "type" => "success",
            "message" => "You have been logged out!"
        ]);
    }



    public function login()
    {
        return view("users.login");
    }


    public function authenticate(Request $request)
    {
        $formFields = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required"]
        ]);

        $user = User::where("email", $request->email)->first();
        if (empty($user)) {
            return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
        }

        if ($user->status == 1) {
            if (auth()->attempt($formFields)) {

                $request->session()->regenerate();
                if (auth()->user()->type == "admin") {
                    return redirect('/admin/dashboard')->with('message', 'You are now logged in as admin!');
                } elseif (auth()->user()->type == "user") {
                    return redirect("/")->with('message', 'You are now logged in!');
                }else {
                    auth()->logout();

                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect("/login")->with('message', 'Undefined user type');
                }
            }
        } else {
            return back()->withErrors(['email' => 'Please activate your account'])->onlyInput('email');
        }

        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }
}
