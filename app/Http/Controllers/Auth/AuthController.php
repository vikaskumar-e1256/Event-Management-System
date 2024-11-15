<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegistrationRequest $request)
    {
        $validatedData = $request->validated();

        $encryptedData = encrypt_data([
            'name' => $validatedData['name'],
            'email' => $validatedData['email']
        ]);

        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => UserRole::ATTENDEE,
            'encrypted_data' => $encryptedData
        ]);

        return response()->json(['message' => 'User registered successfully!']);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        if (Auth::attempt($validatedData)) {
            $user = Auth::user();

            // Redirect based on user role
            if ($user->role === UserRole::ATTENDEE) {
                return response()->json(['success' => true, 'message' => 'Login successful!', 'redirect' => route('home')], 200);
            } elseif ($user->role === UserRole::ORGANIZER) {
                return response()->json(['success' => true, 'message' => 'Login successful!', 'redirect' => route('dashboard')], 200);
            } else {
                // Handle unknown roles
                return response()->json(['success' => false, 'message' => 'Invalid user role!']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Invalid credentials!']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
