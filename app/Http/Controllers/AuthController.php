<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
                'email' => 'Имейлът вече е регистриран'
            ]
        );

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $roleId = Role::where('name', 'User')->first()->id;

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $roleId
        ]);

        return response()->json($user, 200);
    }

    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $u = User::whereEmail($request->email)->first();

        if(!$u) {
            return response()->json(['error' => 'Имейлът не е регистриран'], 401);
        }

        if (auth()->attempt($data)) {
            $user = auth()->user()->load(['info', 'role']);
            $token = auth()->user()->createToken('authToken')->accessToken;
            return response()->json(['token' => $token, 'user' => $user], 200);
        } else {
            return response()->json(['error' => 'Грешна парола'], 401);
        }
    }

    public function logout()
    {
        $user = Auth::user();
        $user->token()->revoke();
        $user->AauthAcessToken()->delete();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
