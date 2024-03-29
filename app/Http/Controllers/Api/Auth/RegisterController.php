<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Models\User;
use App\Rules\Username;
use Illuminate\Http\Request;
use Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        if($request->input('username')) {
            $request->merge(['username' => strtolower($request->input('username'))]);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', new Username, 'max:64', 'min:6', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8']
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = auth('api')->login($user);

        return response()->json(['auth' => AuthResource::make($user), 'token' => $token], 200);
    }
}
