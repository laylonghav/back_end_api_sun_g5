<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Trait\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponseTrait;
    // Register method 
    public function register(Request $req)
    {
        $validated = $req->validate([
            "name" => "required|string|max:255|min:3",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:6|confirmed",
            "role" => "required|string|in:admin,user,teacher"
        ]);

        // Create user
        $user = User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "password" => Hash::make($validated["password"]),
            "role" => $validated["role"],
        ]);

        return $this->apiResponseSuccess($user, "Register successfuuly.", 201);
    }

    // Login method
    public function login(Request $req)
    {
        $validated = $req->validate([
            "email" => "required|email",
            "password" => "required|string|min:6",
        ]);

        // 1. Find user in DB
        $user = User::where("email", $validated["email"])->first();
        if (!$user) {
            return $this->apiNotFound("User not found.");
        }

        // 2. Check Password
        // Hash::check($validated["password"], $user->password) // Return true or false
        if (!Hash::check($validated["password"], $user->password)) {
            return $this->apiResponseError("Incorrect Password.", 401);
        }

        // 3. Generate Token
        $resultToken = $user->createToken("authToken");
        $token = $resultToken->plainTextToken;

        // 4. Cookie
        $cookie = Cookie("authToken", $token, 60 * 24 * 7, "/", null, true, false, true, "Strict");

        return $this->apiResponseSuccess([
            "token" => $token,
            "user" => $user,
            "message" => "user is loged in successfully."
        ], 200)->withCookie($cookie);

    }

    // Logout method
    public function logout(Request $req)
    {
        $user = $req->user()->currentAccessToken()->delete();
        $cookie = Cookie::forget("authToken"); // Delete token
        return $this->apiResponseSuccess(["isLogout" => $user], "User is loged out successfully.", 200)->withCookie($cookie);
    }

    // me method ( Get current user)
    public function me(Request $req)
    {
        $user = $req->user();
        return $this->apiResponseSuccess($user, "Get user successfully.", 200);
    }
}
