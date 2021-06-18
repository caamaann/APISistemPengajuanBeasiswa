<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get(Request $request)
    {
        try {
            if ($request->id) {
                $program_studi = array(ProgramStudi::findOrFail($request->id));
            } else {
                $program_studi = ProgramStudi::all();
            }
            return $this->apiResponse(200, 'success', $program_studi);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    public function storeProgramStudi(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string',
            'jurusan_id' => 'required',
        ]);
        try {
            if (!Jurusan::where('id', $request->jurusan_id)->first){
                return $this->apiResponse(500, 'Jurusan tidak ditemukan', null);
            }
            if (!ProgramStudi::where('nama', $request->nama)->first()) {
                $program_studi = new ProgramStudi;
                $program_studi->nama = $request->nama;
                $program_studi->jurusan_id = $request->jurusan_id;
                $program_studi->save();
                $program_studiData = array($program_studi);
                return $this->apiResponse(200, 'Program Studi berhasil ditambahkan', $program_studiData);
            } else {
                return $this->apiResponse(500, 'Program Studi sudah ada', null);
            }
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    public function updateProgramStudi(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'nama' => 'required|string',
        ]);
        try {
            if (!Jurusan::where('id', $request->jurusan_id)->first){
                return $this->apiResponse(500, 'Jurusan tidak ditemukan', null);
            }
            $program_studi = ProgramStudi::findOrFail($request->id);
            $program_studi->nama = $request->nama;
            $program_studi->jurusan_id = $request->jurusan_id;
            $program_studi->save();
            $program_studiData = array($program_studi);
            return $this->apiResponse(200, 'Program Studi berhasil diubah', $$program_studiData);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    public function destroyProgramStudi(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        try {
            $program_studi = ProgramStudi::findOrFail($request->id);
            $program_studi->delete();
            return $this->apiResponse(200, 'Program Studi berhasil dihapus', null);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }
}
