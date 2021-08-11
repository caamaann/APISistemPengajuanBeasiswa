<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Role;
use Illuminate\Database\Eloquent\Model;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['login']]);
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
            if (!$token = Auth::attempt($credentials, true)) {
                return $this->apiResponse(500, 'Login gagal, periksa username atau password Anda', null);
            }
            $user = User::where('username', $request->username)->firstOrFail();
            $user->profile = $this->getUserProfile($user);
            $roles = $this->getUserRole($user);
            $user->role_code = $roles[0]->name;
            $user->role_name = $roles[0]->display_name;
            $user->token = 'Bearer ' . $token;
            unset($user->roles);

            return $this->apiResponse(200, 'Authentication success', $user);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function change_password(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);

        try {
            $currentPassword = Auth::user()->getAuthPassword();
            if (!app('hash')->check($request->old_password, $currentPassword)) {
                return $this->apiResponse(500, 'Password lama salah', null);
            }
            if (!Str::is($request->new_password, $request->confirm_password)) {
                return $this->apiResponse(500, 'Konfirmasi password baru tidak sama dengan password baru', null);
            }
            $user = User::where('username', Auth::user()->username)->firstOrFail();
            $user->password = app('hash')->make($request->new_password);
            $user->save();
            $user->new_password = $request->new_password;

            return $this->apiResponse(200, 'Password berhasil diganti', $user);
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
