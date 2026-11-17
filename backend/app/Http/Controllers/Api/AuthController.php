<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ], [
            'name.required'     => 'Nama lengkap harus diisi.',
            'email.required'    => 'Email harus diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi harus diisi.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'       => 'success',
            'message'      => 'Pendaftaran berhasil!',
            'data'         => $user,
            'access_token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email harus diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi harus diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        \Illuminate\Support\Facades\Log::info('Login attempt from ' . $request->ip() . ' for ' . $request->email);

        if (!$user || $user->status !== 'active' || !Hash::check($request->password, $user->password)) {
            \Illuminate\Support\Facades\Log::warning('Login failed for: ' . $request->email . ' (user exists: ' . ($user ? 'yes' : 'no') . ')');
            return response()->json([
                'status'  => 'error',
                'message' => 'Email atau kata sandi tidak sesuai.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'       => 'success',
            'message'      => 'Login berhasil!',
            'data'         => $user,
            'access_token' => $token,
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data'   => $request->user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Silakan masuk terlebih dahulu.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
        ], [
            'name.required' => 'Nama harus diisi.',
            'email.unique'  => 'Email sudah digunakan pengguna lain.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Profil berhasil diperbarui!',
            'data'    => $user
        ]);
    }

    public function logout(Request $request)
    {
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Berhasil keluar'
        ]);
    }
}
