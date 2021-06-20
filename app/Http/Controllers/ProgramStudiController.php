<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\ProgramStudi;
use App\Jurusan;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
                $program_studi = array(ProgramStudi::findOrFail($request->id));
            } else {
                $query = ProgramStudi::where('nama', 'like', '%' . $search_text . '%');
                if ($request->jurusan_id) {
                    $query->where('jurusan_id', $request->jurusan_id);
                }

                $count = $query->count();
                $program_studi = $query->skip(($page - 1) * $length)->take($length)->get();
                foreach ($program_studi as $value) {
                    $jurusan = Jurusan::where('id', $value->jurusan_id)->get();
                    $value->jurusan_nama = $jurusan[0]->nama;
                }
            }
            return $this->apiResponseGet(200, $count, $program_studi);
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
            if (!Jurusan::where('id', $request->jurusan_id)->first()) {
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
            if (!Jurusan::where('id', $request->jurusan_id)->first()) {
                return $this->apiResponse(500, 'Jurusan tidak ditemukan', null);
            }
            $program_studi = ProgramStudi::findOrFail($request->id);
            $program_studi->nama = $request->nama;
            $program_studi->jurusan_id = $request->jurusan_id;
            $program_studi->save();
            $program_studiData = array($program_studi);
            return $this->apiResponse(200, 'Program Studi berhasil diubah', $program_studiData);
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
            return $this->apiResponse(200, 'Program Studi berhasil dihapus', $program_studi);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }
}
