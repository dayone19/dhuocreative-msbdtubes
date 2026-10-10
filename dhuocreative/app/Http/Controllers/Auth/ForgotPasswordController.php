<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordOtpMail;

class ForgotPasswordController extends Controller
{
    // nampilkan halaman forgot password
    public function showForgotPassword()
    {
        return view('auth.forgotPass');
    }

    // nerima email dari form
    public function sendOtp(Request $request)
    {
        // valdasi fomat email & nama
        $request->validate([
            'email' => 'required|email',
            'nama'  => 'required|string',
        ]);

        // cari akun dari email & nama
        $user = User::where('email', $request->email)
            ->where('nama', $request->nama)
            ->first();

        // validasi akun ditemukan
        if (! $user) {
            return response()->json([
                'message' => 'Email atau nama tidak sesuai dengan akun.'
            ], 422);
        }

        // buat otp 6 digit
        $otp = (string) random_int(100000, 999999);//buat angka acak 6 digit.

        // simpan hash otp ke database
        DB::table('password_reset_codes')->updateOrInsert(
            ['email' => $user->email],
            [
                'code_hash'    => Hash::make($otp),
                'expires_at'   => now()->addMinutes(5),
                'attempts'     => 0,
                'created_at'   => now(),
            ]
        );

        // kirim otp ke email user
        Mail::to($user->email)->send(
            new ForgotPasswordOtpMail($otp)
        );

        return response()->json([
            'message' => 'Kode OTP berhasil dikirim ke email kamu.'
        ]);
    }

    // verifikasi otp
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'verification_code' => 'required|digits:6',
        ]);

        $resetCode = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->first();

        if (! $resetCode) {
            return response()->json([
                'message' => 'Kode OTP tidak ditemukan. Silakan minta kode baru.'
            ], 422);
        }

        if (now()->greaterThan($resetCode->expires_at)) {
            return response()->json([
                'message' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.'
            ], 422);
        }

        if ($resetCode->attempts >= 5) {
            return response()->json([
                'message' => 'Batas percobaan OTP tercapai. Silakan minta kode baru.'
            ], 422);
        }

        if (! Hash::check($request->verification_code, $resetCode->code_hash)) {
            DB::table('password_reset_codes')
                ->where('email', $request->email)
                ->increment('attempts');

            return response()->json([
                'message' => 'Kode OTP yang kamu masukkan salah.'
            ], 422);
        }

        $request->session()->put('password_reset_verified_email', $request->email);
        $request->session()->put('password_reset_verified_at', now()->timestamp);
        
        return response()->json([
            'message' => 'Verifikasi OTP berhasil.'
        ]);
    }

    // validasi ganti pw baru
    public function resetPassword(Request $request)
    {
        
        $request->validate([
            'new_password' => [
                'required',
                'string',
                'min:7',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/^[a-zA-Z0-9]+$/',
                'same:confirm_password',
            ],
            'confirm_password' => 'required|string',
        ]);

        // Pastikan OTP sudah diverifikasi oleh server
        $email = $request->session()->get('password_reset_verified_email');
        $verifiedAt = $request->session()->get('password_reset_verified_at');

        if (
            ! $email ||
            ! $verifiedAt ||
            now()->timestamp - $verifiedAt > 300
        ) {
            return response()->json([
                'message' => 'Sesi verifikasi OTP sudah berakhir. Silakan verifikasi ulang.'
            ], 422);
        }

        // Pastikan kode OTP masih tersedia dan belum kedaluwarsa
        $resetCode = DB::table('password_reset_codes')
            ->where('email', $email)
            ->first();

        if (! $resetCode || now()->greaterThan($resetCode->expires_at)) {
            return response()->json([
                'message' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.'
            ], 422);
        }

        // SQL: UPDATE users SET pasword = ... WHERE email = ...
        $user = User::where('email', $email)->first();

        if (! $user) {
            return response()->json([
                'message' => 'Akun tidak ditemukan.'
            ], 404);
        }

        $user->pasword = Hash::make($request->new_password);
        $user->save();

        // Hapus OTP agar tidak dapat digunakan kembali
        DB::table('password_reset_codes')
            ->where('email', $email)
            ->delete();

        $request->session()->forget([
            'password_reset_verified_email',
            'password_reset_verified_at',
        ]);

        return response()->json([
            'message' => 'Password berhasil diubah!'
        ]);
    }

}
