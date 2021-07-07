<?php

namespace App\Http\Controllers;

use App\PerbandinganKriteria;
use App\PendaftarBeasiswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Beasiswa;
use App\ProgramStudi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;


class AHPController extends Controller
{
    public function get(Request $request)
    {
        if (!$request->length) {
            $length = 10;
        } else {
            $length = $request->length;
        }
        if (!$request->page) {
            $page = 1;
        } else {
            $page = $request->page;
        }
        if (!$request->search_text) {
            $search_text = "";
        } else {
            $search_text = $request->search_text;
        }
		
		$user = Auth::User();
        try {
            if ($request->id) {
                $beasiswa = array(Beasiswa::findOrFail($request->id));
                $beasiswa[0]->programStudi;
                $pembobotan = PerbandinganKriteria::where('beasiswa_id', $request->id)->get();
                $beasiswa[0]->pembobotan = $pembobotan;
				if($mahasiswa = $user->mahasiswa){
				$hasBeasiswa = PendaftarBeasiswa::where('mahasiswa_id', $mahasiswa->id)->where('beasiswa_id', $request->id)->get();		
				if (count($hasBeasiswa) > 0){
					$beasiswa[0]->status = 1;
				} else {
					$beasiswa[0]->status = 0;
				}
				}
				
                $count = 1;
            } else {
                $query = Beasiswa::where('nama', 'like', '%' . $search_text . '%');
                if ($request->is_active === 1) {
                    $query->where('awal_pendaftaran', '<=', Carbon::now())->where('akhir_pendaftaran', '>=', Carbon::now());
                } else if ($request->is_active === 0) {
                    $query->where('awal_pendaftaran', '>', Carbon::now())->orWhere('akhir_pendaftaran', '<', Carbon::now());
                }

                $count = $query->count();
                $beasiswa = $query->skip(($page - 1) * $length)->take($length)->get();
				
				if($mahasiswa = $user->mahasiswa){
					foreach($beasiswa as $value){
						$hasBeasiswa = PendaftarBeasiswa::where('mahasiswa_id', $mahasiswa->id)->where('beasiswa_id', $value->id)->get();		
						if (count($hasBeasiswa) > 0){
							$value->status = 1;
						} else {
							$value->status = 0;
						}
					}
				}
            }
            return $this->apiResponseGet(200, $count, $beasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    public function countCR(Request $request)
    {
        $this->validate($request, [
            'pembobotan' => 'required',
        ]);

        try {
            $pembobotan = $request->pembobotan;
            $cr = $this->getCRforAHP($pembobotan);
            if ($cr >= 0.1){
                return $this->apiResponse(200, 'Perbandingan tidak konsisten', null);
            }

            return $this->apiResponse(200, 'Perbandingan sudah konsisten', $beasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }
}
