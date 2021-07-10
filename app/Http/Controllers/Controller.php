<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\User;


class Controller extends BaseController
{
    public function apiResponseGet($status, $records_total = 0, $data = null)
    {
        if ($data || empty($data)) {
            return response()->json([
                'status' => $status,
                'recordsTotal' => $records_total,
                'data' => $data
            ]);
        }
        return response()->json([
            'status' => $status,
            'message' => "Terjadi kesalahan di server",
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
            $userProfile = array("nama" => "Administrator");
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
                $userRoles = $roles;
                unset($roles);
            }
            return $userRoles;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getEigenValueForAHP($pembobotan, $total)
    {
        try {
            $length_pembobotan = $total;
            if ($length_pembobotan < 3) {
                return 0.0001;
            }

            $matrix_perbandingan = $this->getMatrixPerbandingan($pembobotan, $length_pembobotan);
            $matrix_perbandingan_for_normalisasi = $matrix_perbandingan;
            // matrix perbandingan ditambahkan total tiap kolom
            $sum[] = null;
            for ($i = 0; $i < $length_pembobotan; $i++) {
                $temp2 = 0;
                for ($j = 0; $j < $length_pembobotan; $j++) {
                    $temp2 += $matrix_perbandingan_for_normalisasi[$j][$i];
                }
                $sum[$i] = $temp2;
            }

            array_push($matrix_perbandingan_for_normalisasi, $sum);

            $matrix_normalisasi = $this->getMatrixNormalisasi($matrix_perbandingan_for_normalisasi, $length_pembobotan);
//            $eigen_value = $this->getEigenValue($matrix_normalisasi, $length_pembobotan);

            return $this->getEigenValue($matrix_normalisasi, $length_pembobotan);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getCRforAHP($pembobotan, $total)
    {
        try {
            $length_pembobotan = $total;
            if ($length_pembobotan < 3) {
                return 0.0001;
            }

            $matrix_perbandingan = $this->getMatrixPerbandingan($pembobotan, $length_pembobotan);
            $matrix_perbandingan_for_normalisasi = $matrix_perbandingan;
            // matrix perbandingan ditambahkan total tiap kolom
            $sum[] = null;
            for ($i = 0; $i < $length_pembobotan; $i++) {
                $temp2 = 0;
                for ($j = 0; $j < $length_pembobotan; $j++) {
                    $temp2 += $matrix_perbandingan_for_normalisasi[$j][$i];
                }
                $sum[$i] = $temp2;
            }

            array_push($matrix_perbandingan_for_normalisasi, $sum);

            $matrix_normalisasi = $this->getMatrixNormalisasi($matrix_perbandingan_for_normalisasi, $length_pembobotan);
            $eigen_value = $this->getEigenValue($matrix_normalisasi, $length_pembobotan);
            $weighted_sum_vector = $this->M_mult($matrix_perbandingan, $eigen_value);
            $eigen_max = $this->getEigenMaks($weighted_sum_vector, $eigen_value);
            $consistency_index = ($eigen_max - $length_pembobotan) / ($length_pembobotan - 1);
            $random_index = $this->getRandomIndex($length_pembobotan);

            //            $consistency_ratio = $consistency_index/$random_index;

            return $consistency_index / $random_index;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getMatrixPerbandingan($pembobotan, $length)
    {
        try {
            $matrix_perbandingan[] = null;
            $temp = 0;
            for ($i = 0; $i < $length; $i++) {
                for ($j = 0; $j < $length; $j++) {
                    if ($i == $j) {
                        $matrix_perbandingan[$i][$j] = 1;
                    } else if ($i < $j) {
                        $v1 = $pembobotan[$temp]["bobot_1"] / $pembobotan[$temp]["bobot_2"];
                        $v2 = $pembobotan[$temp]["bobot_2"] / $pembobotan[$temp]["bobot_1"];
                        $matrix_perbandingan[$i][$j] = $v1;
                        $matrix_perbandingan[$j][$i] = $v2;
                        $temp++;
                    }
                }
            }

            return $matrix_perbandingan;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getMatrixNormalisasi($matrix_perbandingan, $length)
    {
        try {
            $matrix_normalisasi = null;
            for ($i = 0; $i < $length; $i++) {
                for ($j = 0; $j < $length; $j++) {
                    $matrix_normalisasi[$i][$j] = $matrix_perbandingan[$i][$j] / $matrix_perbandingan[$length][$j];
                }
            }

            return $matrix_normalisasi;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getEigenValue($weighted_sum_vector, $length)
    {
        try {
            $eigen[] = null;
            for ($i = 0; $i < $length; $i++) {
                $temp2 = 0;
                for ($j = 0; $j < $length; $j++) {
                    $temp2 += $weighted_sum_vector[$i][$j];
                }
                $eigen[$i][0] = $temp2 / $length;
            }

            return $eigen;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getEigenMaks($weighted_sum_vector, $eigen_value)
    {
        try {
            $length = count($weighted_sum_vector);
            for ($i = 0; $i < $length; $i++) {
                $temp[$i] = $weighted_sum_vector[$i][0] / $eigen_value[$i][0];
            }

            $eigen_max = array_sum($temp) / count($temp);;

            return $eigen_max;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function M_mult($_A, $_B)
    {
        try {

            // AxB outcome is C with A's rows and B'c cols
            $r = count($_A);
            $c = count($_B[0]);
            $in = count($_B); // or $_A[0]. $in is 'inner' count

            if ($in != count($_A[0])) {
                throw new Exception("Ukuran matrix tidak valid");
            }

            // allocate retval
            $retval = array();
            for ($i = 0; $i < $r; $i++) {
                $retval[$i] = array();
                // multiplication here
                for ($ri = 0; $ri < $r; $ri++) {
                    for ($ci = 0; $ci < $c; $ci++) {
                        $retval[$ri][$ci] = 0.0;
                        for ($j = 0; $j < $in; $j++) {
                            $retval[$ri][$ci] += $_A[$ri][$j] * $_B[$j][$ci];
                        }
                    }
                }
                return $retval;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getRandomIndex($length_matrix)
    {
        try {
            switch ($length_matrix) {
                case 1:
                    return RANDOM_INDEX_1;
                case 2:
                    return RANDOM_INDEX_2;
                case 3:
                    return RANDOM_INDEX_3;
                case 4:
                    return RANDOM_INDEX_4;
                case 5:
                    return RANDOM_INDEX_5;
                case 6:
                    return RANDOM_INDEX_6;
                case 7:
                    return RANDOM_INDEX_7;
                case 8:
                    return RANDOM_INDEX_8;
                case 9:
                    return RANDOM_INDEX_9;
                case 10:
                    return RANDOM_INDEX_10;
                default:
                    return null;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getTotalFromPembobotan($pembobotan)
    {
        try {
            switch ($pembobotan) {
                case 1:
                    return 2;
                case 3:
                    return 3;
                case 6:
                    return 4;
                case 10:
                    return 5;
                case 15:
                    return 6;
                case 21:
                    return 7;
                case 28:
                    return 8;
                case 36:
                    return 9;
                case 45:
                    return 10;
                case 55:
                    return 11;
                case 66:
                    return 12;
                case 78:
                    return 13;
                case 91:
                    return 14;
                case 105:
                    return 15;
                default:
                    return null;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
