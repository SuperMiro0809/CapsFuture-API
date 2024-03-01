<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\{
    User,
    Role
};

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = validator($request->only('email', 'password','mode'), 
            [
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ],
            [
                'email' => 'validation.email.registered',
                'password' => 'validation.password.min'
            ]
        );

        if ($validator->fails()) {
            return response(['message'=>$validator->errors()->all()], 422);
        }

        $roleId = Role::where('name', 'User')->first()->id;

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $roleId
        ]);

        $user->profile()->create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName
        ]);

        $token = $user->createToken('authToken')->accessToken;

        $user->load(['role', 'profile']);
        return response()->json(['accessToken' => $token, 'user' => $user], 200);
    }

    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $u = User::whereEmail($request->email)->first();

        if(!$u) {
            return response()->json(['message' => 'Имейлът не е регистриран'], 401);
        }

        if (auth()->attempt($data)) {
            $user = auth()->user()->load(['role', 'profile']);
            $token = auth()->user()->createToken('authToken')->accessToken;
            return response()->json(['accessToken' => $token, 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'Грешна парола'], 401);
        }
    }

    public function logout()
    {
        $user = Auth::user();
        $user->token()->revoke();
        $user->AauthAcessToken()->delete();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function profile()
    {
        $user = auth()->user()->load(['role', 'profile']);

        return response()->json($user, 200);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        switch($status) {
            case Password::RESET_LINK_SENT:
                return response()->json($status);
                break;
            case Password::INVALID_USER:
                return response()->json(['message' => 'validation.email.not-registered'], 422);
                break;
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
     
        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);
     
                $user->save();
            }
        );

        switch($status) {
            case Password::PASSWORD_RESET:
                return response()->json($status);
                break;
            case Password::INVALID_TOKEN:
                return response()->json(['message' => 'Линкът не е активен'], 400);
                break;
        }
    }
}
