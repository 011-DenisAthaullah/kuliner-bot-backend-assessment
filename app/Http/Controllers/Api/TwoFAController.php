<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class TwoFAController extends Controller
{
    // Enable 2FA untuk user
    public function enable()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update(['two_fa_enable' => true]);

        return response()->json([
            'status'  => true,
            'message' => '2FA berhasil diaktifkan',
        ]);
    }

    // Disable 2FA untuk user
    public function disable()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'two_fa_enable' => false,
            'two_fa_secret' => null,
        ]);

        return response()->json([
            'status'  => true,
            'message' => '2FA berhasil dinonaktifkan',
        ]);
    }

    // Generate & simpan OTP (dipanggil dari AuthController@login)
    public function sendOtp(User $user): string
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'two_fa_secret'     => $otp,
            'two_fa_expires_at' => now()->addMinutes(5),
        ]);

        return $otp;
    }

    // Verify OTP saat login
    public function verify(Request $request)
    {
        $request->validate([
            'two_fa_token' => 'required|string',
            'otp'          => 'required|string|size:6',
        ]);

        // Decrypt user id dari temporary token
        $userId = decrypt($request->two_fa_token);

        /** @var \App\Models\User $user */
        $user = User::findOrFail($userId);

        // ← Tambah cek null di sini
        if (is_null($user->two_fa_expires_at)) {
            return response()->json([
                'status'  => false,
                'message' => 'OTP tidak ditemukan, silakan login ulang',
            ], 422);
        }

        // Cek OTP expired
        if (now()->isAfter($user->two_fa_expires_at)) {
            return response()->json([
                'status'  => false,
                'message' => 'OTP sudah expired, silakan login ulang',
            ], 422);
        }

        // Cek OTP cocok
        if ($request->otp !== $user->two_fa_secret) {
            return response()->json([
                'status'  => false,
                'message' => 'OTP tidak valid',
            ], 422);
        }

        // Hapus OTP setelah berhasil digunakan
        $user->update([
            'two_fa_secret'     => null,
            'two_fa_expires_at' => null,
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status'       => true,
            'message'      => 'Login dengan 2FA berhasil',
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => config('jwt.ttl') * 60,
            'user'         => $user,
        ]);
    }
}