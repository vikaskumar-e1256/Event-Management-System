<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegistrationRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth::auth.register');
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
        return view('auth::auth.login');
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
