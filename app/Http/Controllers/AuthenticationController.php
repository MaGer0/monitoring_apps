<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'password' => 'required'
        ]);

        $teacher = Teacher::where('nik', $request->nik)->first();

        if (!$teacher || ! Hash::check($request->password, $teacher->password)) {
            throw ValidationException::withMessages([
                "Login Gagal"
            ]);
        }
        return $teacher->createToken('user login')->plainTextToken;
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
}
