<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Role;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout']]);
        $this->middleware('auth', ['only' => ['logout']]);
    }

    // public function register(Request $request)
    // {
    //     $this->validate($request, [
    //         'username' => 'required|unique:users',
    //         'password' => 'required|confirmed',
    //     ]);

    //     try {
    //         $user = new User;
    //         $user->username = $request->username;
    //         $user->password = app('hash')->make($request->password);
    //         $user->save();
    //         $mahasiswaRole = Role::where('name', 'mahasiswa')->firstOrFail();
    //         $user->roles()->attach($mahasiswaRole->id);
    //         return $this->apiResponse(200, 'User Created', ['user'=>$user]);
    //     } catch (\Exception $e) {
    //         return $this->apiResponse(201, 'Registration Failed', null);
    //     }

    // }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['username', 'password']);
        try {
            if (!$token = Auth::attempt($credentials)) {
                return $this->apiResponse(201, 'Wrong credentials', null);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return $this->apiResponse(500, 'Token Expired', null);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return $this->apiResponse(500, 'Token Invalid', null);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
        try {
            $user = User::where('username', $request->username)->firstOrFail();
            $user->profile = $this->getUserProfile($user);
            $user->roles = $this->getUserRole($user);
            $credential = array(
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() . ' minutes',
                'user_data' => $user,
            );
            return $this->apiResponse(200, 'Authentication success', ['credential' => $credential]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function logout()
    {
        Auth::invalidate();
        return $this->apiResponse(200, 'token invalidated', null);
    }

}
