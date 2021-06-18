<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\User;


class Controller extends BaseController
{
	public function apiResponseGet($status, $records_total = 0, $data = null)
    {
        if ($data) {
            return response()->json([
                'status' => $status,
                'recordsTotal' => $records_total,
                'data' => $data
            ]);
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], 500);
    }
	
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
        ], 500);
    }

    public function getUserProfile($user)
    {
        try {
            $userProfile = array("nama"=>"Administrator");
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
            //$userRoles = "User ini belum memiliki role";
            if ($user->roles()->exists()) {
                $roles = $user->roles;
                $userRoles = $roles[0];
            }
            return $userRoles;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
