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
    public function getAll()
    {
        try {
            $list_beasiswa = Beasiswa::all();
            foreach ($list_beasiswa as $beasiswa) {
                $beasiswa->programStudi = $beasiswa->programStudi;
            }
            return $this->apiResponse(200, 'success', ['beasiswa' => $list_beasiswa]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
        ]);
        try {
            $beasiswa = Beasiswa::findOrFail($request->beasiswa_id);
            return $this->apiResponse(200, 'success', ['beasiswa' => $beasiswa]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getActive()
    {
        try {
            $list_beasiswa = Beasiswa::where('awal_pendaftaran', '<=', Carbon::now())->where('akhir_pendaftaran', '>=', Carbon::now())->get();
            return $this->apiResponse(200, 'success', ['beasiswa' => $list_beasiswa]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }
}
