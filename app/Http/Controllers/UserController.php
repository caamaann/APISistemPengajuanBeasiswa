<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;


class UserController extends Controller
{
    /**
     * Instantiate a new UserController instance that guarded by auth and role middleware.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::User();
            $this->validate($request, [
                'username' => 'unique:users,username,' . $user->id,
            ]);
            $user = User::findOrFail($user->id);
            if ($request->username) {
                $user->username = $request->username;
            }
            if ($request->password) {
                $user->password = app('hash')->make($request->password);
            }
            $user->save();
            return $this->apiResponse(200, 'success', ['user' => $user]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function profile()
    {
        try {
            $user = Auth::User();
            $user->profile = $this->getUserProfile($user);
            $user->roles = $this->getUserRole($user);
            return $this->apiResponse(200, 'success', ['user' => $user]);
        } catch (Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }
}
