<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\User;


class Controller extends BaseController
{
    public function apiResponse($status, $message, $result = null)
    {
        if ($result) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $result
            ]);
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function getUserProfile($user)
    {
        try {
            $userProfile = "User ini belum mengisi profil";
            if ($user->mahasiswa()->exists()) {
                $userProfile = $user->mahasiswa;
                unset($user->mahasiswa);
            } else if ($user->waliKelas()->exists()) {
                $userProfile = $user->waliKelas;
                unset($user->waliKelas);
            } else if ($user->ketuaProgramStudi()->exists()) {
                $userProfile = $user->ketuaProgramStudi;
                unset($user->ketuaProgramStudi);
            } else if ($user->ketuaJurusan()->exists()) {
                $userProfile = $user->ketuaJurusan;
                unset($user->ketuaJurusan);
            } else if ($user->pembantuDirektur3()->exists()) {
                $userProfile = $user->pembantuDirektur3;
                unset($user->pembantuDirektur3);
            }
            return $userProfile;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getUserRole($user)
    {
        try {
            $userRoles = "User ini belum memiliki role";
            if ($user->roles()->exists()) {
                $roles = $user->roles;
                $userRoles = [];
                foreach ($roles as $key => $role) {
                    unset($role->pivot);
                    unset($role->created_at);
                    unset($role->updated_at);
                    array_push($userRoles, $role);
                }
            }
            unset($user->roles);
            return $userRoles;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
