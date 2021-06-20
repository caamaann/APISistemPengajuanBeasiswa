<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Beasiswa;
use App\ProgramStudi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;


class BeasiswaController extends Controller
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

        try {
            if ($request->id) {
                $beasiswa = array(Beasiswa::findOrFail($request->id));
                $program_studi = array();
                foreach ($beasiswa[0]->programStudi as $item) {
                    $list_kuota = array(
                        'id' => $item->id,
                        'nama' => $item->nama,
                        'angkatan' => $item->pivot->angkatan,
                        'kuota' => $item->pivot->kuota,
                    );
                    array_push($program_studi, $list_kuota);
                }
                $beasiswa[0]->programStudi = $program_studi;
            } else {
                $query = Beasiswa::where('nama', 'like', '%' . $search_text . '%');
                if ($request->is_active === 1) {
                    $query->where('awal_pendaftaran', '<=', Carbon::now())->where('akhir_pendaftaran', '>=', Carbon::now());
                } else if ($request->is_active === 0) {
                    $query->where('awal_pendaftaran', '>', Carbon::now())->orWhere('akhir_pendaftaran', '<', Carbon::now());
                }

                $count = $query->count();
                $beasiswa = $query->skip(($page - 1) * $length)->take($length)->get();
            }
            return $this->apiResponseGet(200, $count, $beasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    public function storeBeasiswa(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string',
            'deskripsi' => 'string',
            'awal_pendaftaran' => 'required|date|before:akhir_pendaftaran',
            'akhir_pendaftaran' => 'required|date|after:awal_pendaftaran',
            'awal_penerimaan' => 'required|date|after:awal_pendaftaran|after:akhir_pendaftaran|before:akhir_penerimaan',
            'akhir_penerimaan' => 'required|date|after:awal_pendaftaran|after:akhir_pendaftaran|after:awal_penerimaan',
            'biaya_pendidikan_per_semester' => 'required|integer',
            'penghasilan_orang_tua_maksimal' => 'required|integer',
            'ipk_minimal' => 'required',
            'bobot_ipk' => 'required|integer|gt:0|lte:100',
            'bobot_prestasi' => 'required|integer|gt:0|lte:100',
            'bobot_perilaku' => 'required|integer|gt:0|lte:100',
            'bobot_organisasi' => 'required|integer|gt:0|lte:100',
            'bobot_kemampuan_ekonomi' => 'required|integer|gt:0|lte:100',

        ]);

        try {
            if ($request->bobot_ipk + $request->bobot_prestasi + $request->bobot_perilaku + $request->bobot_organisasi + $request->bobot_kemampuan_ekonomi != 100) {
                return $this->apiResponse(201, 'Bobot Penilaian Tidak Sesuai', null);
            }
            $beasiswa = Beasiswa::create($request->all());
            return $this->apiResponse(200, 'success', ['beasiswa' => $beasiswa]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function updateBeasiswa(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
            'nama' => 'string',
            'deskripsi' => 'string',
            'awal_pendaftaran' => 'date|before:akhir_pendaftaran',
            'akhir_pendaftaran' => 'date|after:awal_pendaftaran',
            'awal_penerimaan' => 'date|after:awal_pendaftaran|after:akhir_pendaftaran|before:akhir_penerimaan',
            'akhir_penerimaan' => 'date|after:awal_pendaftaran|after:akhir_pendaftaran|after:awal_penerimaan',
            'biaya_pendidikan_per_semester' => 'integer',
            'penghasilan_orang_tua_maksimal' => 'integer',
            'ipk_minimal' => 'required',
            'bobot_ipk' => 'required|integer|gt:0|lte:100',
            'bobot_prestasi' => 'required|integer|gt:0|lte:100',
            'bobot_perilaku' => 'required|integer|gt:0|lte:100',
            'bobot_organisasi' => 'required|integer|gt:0|lte:100',
            'bobot_kemampuan_ekonomi' => 'required|integer|gt:0|lte:100',
        ]);
        try {
            if ($request->bobot_ipk + $request->bobot_prestasi + $request->bobot_perilaku + $request->bobot_organisasi + $request->bobot_kemampuan_ekonomi != 100) {
                return $this->apiResponse(201, 'Bobot Penilaian Tidak Sesuai', null);
            }
            $beasiswa = Beasiswa::findOrFail($request->beasiswa_id);
            $beasiswa->update($request->all());
            return $this->apiResponse(200, 'success', $beasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function destroyBeasiswa(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        try {
            $beasiswa = Beasiswa::findOrFail($request->id);
            Beasiswa::destroy($request->beasiswa_id);
            return $this->apiResponse(200, 'success', $beasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }
}
