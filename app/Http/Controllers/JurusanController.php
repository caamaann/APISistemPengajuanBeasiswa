<?php

namespace App\Http\Controllers;

use App\ProgramStudi;
use Illuminate\Support\Facades\Auth;
use App\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get(Request $request)
    {
        try {
            if ($request->id) {
                $jurusan = array(Jurusan::findOrFail($request->id));
                $jurusan[0]->program_studi = ProgramStudi::where('jurusan_id', $request->id)->get();
            } else {
                $jurusan = Jurusan::all();
                foreach ($jurusan as $value){
                    $value->program_studi = ProgramStudi::where('jurusan_id', $value->id)->get();
                }
            }
            return $this->apiResponse(200, 'success', $jurusan);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    public function storeJurusan(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string',
        ]);
        try {
            if (!Jurusan::where('nama', $request->nama)->first()) {
                $jurusan = new Jurusan;
                $jurusan->nama = $request->nama;
                $jurusan->save();
                $jurusanData = array($jurusan);
                return $this->apiResponse(200, 'Jurusan berhasil ditambahkan', $jurusanData);
            } else {
                return $this->apiResponse(500, 'Jurusan sudah ada', null);
            }
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    public function updateJurusan(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'nama' => 'required|string',
        ]);
        try {
            $jurusan = Jurusan::findOrFail($request->id);
            $jurusan->nama = $request->nama;
            $jurusan->save();
            $jurusanData = array($jurusan);
            return $this->apiResponse(200, 'Jurusan berhasil diubah', $jurusanData);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    public function destroyJurusan(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        try {
            $jurusan = Jurusan::findOrFail($request->id);
            $jurusan->delete();
            return $this->apiResponse(200, 'Jurusan berhasil dihapus', null);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }
}
