<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    public function get(Request $request)
    {
        $this->validate($request, [
            'program_studi_id' => 'required|integer',
        ]);
        try {
            $programStudi = ProgramStudi::findOrFail($request->program_studi_id);
            return $this->apiResponse(200, 'success', ['program_studi' => $programStudi]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getAll()
    {
        try {
            $listProgramStudi = ProgramStudi::all();
            return $this->apiResponse(200, 'success', ['program_studi' => $listProgramStudi]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getProgramStudiByJurusan(Request $request)
    {
        $this->validate($request, [
            'jurusan_id' => 'required|integer',
        ]);
        try {
            $listProgramStudi = ProgramStudi::where('jurusan_id', $request->jurusan_id)->get();
            return $this->apiResponse(200, 'success', ['program_studi' => $listProgramStudi]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }
}
