<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'error'   => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status'  => true,
            'message' => 'Registrasi Berhasil',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'error'   => $validator->errors()
            ], 422);
        }

        if (!$token = Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status'  => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        $user = Auth::user();

        if ($user->two_fa_enable) {
            // Kirim OTP
            $otp = app(TwoFAController::class)->sendOtp($user);

            return response()->json([
                'status'          => true,
                'message'         => 'OTP diperlukan',
                'two_fa_required' => true,
                'two_fa_token'    => encrypt($user->id),
                'otp'             => $otp, // TODO: Remove in production, send via email
            ], 200);
        }

        return $this->respondWithToken($token);
    }

    // Logout
    public function logout()
    {
        Auth::logout();

        return response()->json([
            'status'  => true,
            'message' => 'Logout berhasil',
        ]);
    }

    // Refresh Token
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    // Profile user yang sedang login
    public function me()
    {
        return response()->json([
            'status' => true,
            'user'   => Auth::user(),
        ]);
    }

    // Helper format response token
    private function respondWithToken($token)
    {
        return response()->json([
            'status'       => true,
            'message'      => 'Login berhasil',
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => config('jwt.ttl') * 60,
            'user'         => Auth::user(),
        ]);
    }
}