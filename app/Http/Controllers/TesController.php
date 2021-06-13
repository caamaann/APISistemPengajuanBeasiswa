<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Beasiswa;
use App\ProgramStudi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;


class TesController extends Controller
{
    public function index()
    {
        return DB::table('pendaftar_beasiswa')->get();
    }
}
