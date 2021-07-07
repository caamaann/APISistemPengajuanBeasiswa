<?php

namespace App\Http\Controllers;

use App\ProgramStudi;
use Illuminate\Support\Facades\Auth;
use App\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
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
                $kriteria = array(Kriteria::findOrFail($request->id));
                $count = 1;
            } else {
                $query = Kriteria::where('nama', 'like', '%' . $search_text . '%');

                $count = $query->count();
                $kriteria = $query->skip(($page - 1) * $length)->take($length)->get();
                foreach ($kriteria as $value) {
                    $value->program_studi = ProgramStudi::where('kriteria_id', $value->id)->get();
                }
            }
            return $this->apiResponseGet(200, $count, $kriteria);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    public function storeKriteria(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string',
        ]);
        try {
            if (!Kriteria::where('nama', $request->nama)->first()) {
                $kriteria = new Kriteria;
                $kriteria->nama = $request->nama;
                $kriteria->save();
                $kriteriaData = array($kriteria);
                return $this->apiResponse(200, 'Kriteria berhasil ditambahkan', $kriteriaData);
            } else {
                return $this->apiResponse(500, 'Kriteria sudah ada', null);
            }
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    public function updateKriteria(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'nama' => 'required|string',
        ]);
        try {
            $kriteria = Kriteria::findOrFail($request->id);
            $kriteria->nama = $request->nama;
            $kriteria->save();
            $kriteriaData = array($kriteria);
            return $this->apiResponse(200, 'Kriteria berhasil diubah', $kriteriaData);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    public function destroyKriteria(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        try {
            $kriteria = Kriteria::findOrFail($request->id);
            $kriteria->delete();
            return $this->apiResponse(200, 'Kriteria berhasil dihapus', $kriteria);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }
}
