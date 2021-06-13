<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function get(Request $request)
    {
        $this->validate($request, [
            'jurusan_id' => 'required|integer',
        ]);
        try {
            $jurusan = Jurusan::findOrFail($request->jurusan_id);
            return $this->apiResponse(200, 'success', ['jurusan' => $jurusan]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getAll()
    {
        try {
            $listJurusan = Jurusan::all();
            return $this->apiResponse(200, 'success', ['jurusan' => $listJurusan]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }
}
