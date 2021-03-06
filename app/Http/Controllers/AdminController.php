<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Mahasiswa;
use App\WaliKelas;
use App\KetuaProgramStudi;
use App\KetuaJurusan;
use App\PembantuDirektur3;
use App\ProgramStudi;
use App\Jurusan;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;


class AdminController extends Controller
{     
    public function __construct()
    {                
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function getAllUser()
    {   
        $users = User::all();
        foreach ($users as $user) {
            $user->profile = $this->getUserProfile($user);
            $user->roles = $this->getUserRole($user);
        }
        return $this->apiResponse(200, 'success', ['user' => $users]);        
    }
    
    public function getUser(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',            
        ]);
        try {            
            $user = User::where('username', $request->username)->firstOrFail();
            $user->profile = $this->getUserProfile($user);
            $user->roles = $this->getUserRole($user);
            return $this->apiResponse(200, 'success', ['user' => $user]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getAllMahasiswa()
    {
        try {                        
            return $this->apiResponse(200, 'List Wali Kelas', ['mahasiswa' => Mahasiswa::with('user', 'waliKelas', 'programStudi')->get()]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getMahasiswa(Request $request)
    {
        $this->validate($request, [            
            'id' => 'required|integer',            
        ]);
        try {                        
            return $this->apiResponse(200, 'Wali kelas', ['mahasiswa' => Mahasiswa::where('id', $request->id)->with('user', 'waliKelas', 'programStudi')->firstOrFail()]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function storeMahasiswa(Request $request)
    {
        $this->validate($request, [
            'nim' => 'required|string|max:10|unique:mahasiswa,nim|unique:users,username',
            'nama' => 'required|string',
            'email' => 'required|email|unique:mahasiswa,email',
            'wali_kelas_id' => 'required|integer',
            'program_studi_id' => 'required|integer',
            'semester' => 'required|integer|gt:0|lt:8',
            'angkatan' => 'required|integer|gt:0',
            'ipk' => 'required|gt:0|lt:4',
        ]);
        try {
            $user = new User;
            $waliKelas = WaliKelas::findOrFail($request->wali_kelas_id);
            $programStudi = ProgramStudi::findOrFail($request->program_studi_id);
            $mahasiswaRole = Role::where('name', 'mahasiswa')->firstOrFail();            
            $user->username = $request->nim;
            $user->password = app('hash')->make($request->nim);
            $user->save();
            $user->roles()->attach($mahasiswaRole->id);
            $mahasiswa = new Mahasiswa;
            $mahasiswa->user_id = $user->id;
            $mahasiswa->nim = $request->nim;
            $mahasiswa->nama = $request->nama;
            $mahasiswa->email = $request->email;
            $mahasiswa->semester = $request->semester;
            $mahasiswa->angkatan = $request->angkatan;
            $mahasiswa->ipk = $request->ipk;
            $mahasiswa->wali_kelas_id = $waliKelas->id;
            $mahasiswa->program_studi_id = $programStudi->id;
            $mahasiswa->save();
            $mahasiswa_data = array(
                'mahasiswa' => $mahasiswa,
                'user' => $user
            );
            return $this->apiResponse(200, 'Mahasiswa created', ['mahasiswa' => $mahasiswa_data]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function updateMahasiswa(Request $request)
    {
        $mahasiswa = Mahasiswa::findOrFail($request->id);            
        $this->validate($request, [
            'id' => 'required|integer',
            'nim' => 'required|string|max:10|unique:mahasiswa,nim,'.$mahasiswa->id,
            'nama' => 'required|string',
            'email' => 'required|email|unique:mahasiswa,email,'.$mahasiswa->id,
            'wali_kelas_id' => 'required|integer',
            'program_studi_id' => 'required|integer',
            'semester' => 'required|integer|gt:0|lt:8',
            'angkatan' => 'required|integer|gt:0',
            'ipk' => 'required|gt:0|lt:4',
        ]);
        try {
            $user = $mahasiswa->user;
            $user->username = $request->nim;
            $user->password = app('hash')->make($request->nim);
            $user->save();
            $mahasiswa->nim = $request->nim;
            $mahasiswa->nama = $request->nama;
            $mahasiswa->email = $request->email;
            $mahasiswa->semester = $request->semester;
            $mahasiswa->angkatan = $request->angkatan;
            $mahasiswa->ipk = $request->ipk;
            $waliKelas = WaliKelas::findOrFail($request->wali_kelas_id);
            $mahasiswa->wali_kelas_id = $waliKelas->id;
            $programStudi = ProgramStudi::findOrFail($request->program_studi_id);
            $mahasiswa->program_studi_id = $programStudi->id;
            $mahasiswa->save();
            return $this->apiResponse(200, 'mahasiswa updated', ['mahasiswa' => $mahasiswa]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function destroyMahasiswa(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',            
        ]);
        try {            
            $mahasiswa = Mahasiswa::findOrFail($request->id);            
            $user = $mahasiswa->user;
            $mahasiswa->delete();
            $user->delete();
            return $this->apiResponse(200, 'Wali kelas destroy', null);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }


    public function getAllWaliKelas()
    {
        try {                        
            return $this->apiResponse(200, 'List Wali Kelas', ['wali_kelas' => WaliKelas::with('user', 'jurusan')->get()]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getWaliKelas(Request $request)
    {
        $this->validate($request, [            
            'id' => 'required|integer',            
        ]);
        try {                        
            return $this->apiResponse(200, 'Wali kelas', ['wali_kelas' => WaliKelas::where('id', $request->id)->with('user', 'jurusan')->firstOrFail()]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function storeWaliKelas(Request $request)
    {
        $this->validate($request, [
            'nip' => 'required|string|max:20',
            'nama' => 'required|string',
            'jurusan_id' => 'required|integer',
        ]);
        try {            
            $jurusan = Jurusan::findOrFail($request->jurusan_id);
            $waliKelasRole = Role::where('name', 'waliKelas')->firstOrFail();
            $user = new User;
            $user->username = $request->nip;
            $user->password = app('hash')->make($request->nip);
            $user->save();
            $user->roles()->attach($waliKelasRole->id);
            $waliKelas = new WaliKelas;
            $waliKelas->jurusan_id = $jurusan->id;
            $waliKelas->user_id = $user->id;
            $waliKelas->nip = $request->nip;
            $waliKelas->nama = $request->nama;
            $waliKelas->save();
            $waliKelasData = array(
                'waliKelas' => $waliKelas,
                'user' => $user
            );
            return $this->apiResponse(200, 'Wali kelas created', ['wali_kelas' => $waliKelasData]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function updateWaliKelas(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
            'nip' => 'required|string|max:20',
            'nama' => 'required|string',
            'jurusan_id' => 'required|integer',            
        ]);
        try {            
            $waliKelas = WaliKelas::findOrFail($request->id);
            $jurusan = Jurusan::findOrFail($request->jurusan_id);
            $waliKelas->user->username = $request->nip;
            $waliKelas->user->password = app('hash')->make($request->nip);
            $waliKelas->user->save();
            $waliKelas->jurusan_id = $jurusan->id;
            $waliKelas->nip = $request->nip;
            $waliKelas->nama = $request->nama;
            $waliKelas->save();
            $waliKelasData = array(
                'waliKelas' => $waliKelas,
                'user' => $user
            );
            return $this->apiResponse(200, 'Wali kelas updated', ['wali_kelas' => $waliKelasData]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function destroyWaliKelas(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',            
        ]);
        try {            
            $waliKelas = WaliKelas::findOrFail($request->id);            
            $user = $waliKelas->user;
            $waliKelas->delete();
            $user->delete();
            return $this->apiResponse(200, 'Wali kelas destroy', null);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getAllKetuaProgramStudi()
    {
        try {                        
            return $this->apiResponse(200, 'List Ketua Program Studi', ['ketua_program_studi' => KetuaProgramStudi::with('user', 'programStudi')->get()]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getKetuaProgramStudi(Request $request)
    {
        $this->validate($request, [            
            'id' => 'required|integer',            
        ]);
        try {                        
            return $this->apiResponse(200, 'Ketua Program Studi', ['ketua_program_studi' => KetuaProgramStudi::where('id', $request->id)->with('user', 'programStudi')->firstOrFail()]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function storeKetuaProgramStudi(Request $request)
    {
        $this->validate($request, [
            'nip' => 'required|string|max:20',
            'nama' => 'required|string',
            'program_studi_id' => 'required|integer',
        ]);
        try {            
            $programStudi = ProgramStudi::findOrFail($request->program_studi_id);
            $ketuaProgramStudiRole = Role::where('name', 'ketuaProdi')->firstOrFail();
            $user = new User;
            $user->username = $request->nip;
            $user->password = app('hash')->make($request->nip);
            $user->save();
            $user->roles()->attach($ketuaProgramStudiRole->id);
            $ketuaProgramStudi = new KetuaProgramStudi;
            $ketuaProgramStudi->program_studi_id = $programStudi->id;
            $ketuaProgramStudi->user_id = $user->id;
            $ketuaProgramStudi->nip = $request->nip;
            $ketuaProgramStudi->nama = $request->nama;
            $ketuaProgramStudi->save();
            $ketuaProgramStudiData = array(
                'ketuaProgramStudi' => $ketuaProgramStudi,
                'user' => $user
            );
            return $this->apiResponse(200, 'Ketua Program Studi created', ['ketua_program_studi' => $ketuaProgramStudiData]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function updateKetuaProgramStudi(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
            'nip' => 'required|string|max:20',
            'nama' => 'required|string',
            'program_studi_id' => 'required|integer',
        ]);
        try {            
            $ketuaProgramStudi = KetuaProgramStudi::findOrFail($request->id);
            $programStudi = ProgramStudi::findOrFail($request->program_studi_id);
            $user = $ketuaProgramStudi->user;
            $user->username = $request->nip;
            $user->password = app('hash')->make($request->nip);
            $user->save();            
            $ketuaProgramStudi->program_studi_id = $programStudi->id;            
            $ketuaProgramStudi->nip = $request->nip;
            $ketuaProgramStudi->nama = $request->nama;
            $ketuaProgramStudi->save();
            $ketuaProgramStudiData = array(
                'ketuaProgramStudi' => $ketuaProgramStudi,
                'user' => $user
            );
            return $this->apiResponse(200, 'Ketua Program Studi updated', ['ketua_program_studi' => $ketuaProgramStudiData]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function destroyKetuaProgramStudi(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',            
        ]);
        try {            
            $ketuaProgramStudi = KetuaProgramStudi::findOrFail($request->id);            
            $user = $ketuaProgramStudi->user;
            $ketuaProgramStudi->delete();            
            $user->delete();                        
            return $this->apiResponse(200, 'Ketua Program Studi deleted', null);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getAllKetuaJurusan()
    {
        try {                        
            return $this->apiResponse(200, 'List Ketua Jurusan', ['ketua_jurusan' => KetuaJurusan::with('user', 'jurusan')->get()]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getKetuaJurusan(Request $request)
    {
        $this->validate($request, [            
            'id' => 'required|integer',            
        ]);
        try {                        
            return $this->apiResponse(200, 'Ketua Jurusan', ['ketua_jurusan' => KetuaJurusan::where('id', $request->id)->with('user', 'jurusan')->firstOrFail()]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function storeKetuaJurusan(Request $request)
    {
        $this->validate($request, [            
            'nip' => 'required|string|max:20',
            'nama' => 'required|string',
            'jurusan_id' => 'required|integer',
        ]);
        try {            
            $jurusan = Jurusan::findOrFail($request->jurusan_id);
            $ketuaJurusanRole = Role::where('name', 'ketuaJurusan')->firstOrFail();
            $user = new User;
            $user->username = $request->nip;
            $user->password = app('hash')->make($request->nip);
            $user->save();
            $user->roles()->attach($ketuaJurusanRole->id);
            $ketuaJurusan = new KetuaJurusan;
            $ketuaJurusan->jurusan_id = $jurusan->id;
            $ketuaJurusan->user_id = $user->id;
            $ketuaJurusan->nip = $request->nip;
            $ketuaJurusan->nama = $request->nama;
            $ketuaJurusan->save();
            $ketuaJurusanData = array(
                'ketuaJurusan' => $ketuaJurusan,
                'user' => $user
            );
            return $this->apiResponse(200, 'Ketua Jurusan created', ['ketua_jurusan' => $ketuaJurusanData]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function updateKetuaJurusan(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
            'nip' => 'required|string|max:20',
            'nama' => 'required|string',
            'jurusan_id' => 'required|integer',
        ]);
        try {            
            $ketuaJurusan = KetuaJurusan::findOrFail($request->id);
            $user = $ketuaJurusan->user;
            $user->username = $request->nip;
            $user->password = app('hash')->make($request->nip);
            $user->save();            
            $jurusan = Jurusan::findOrFail($request->jurusan_id);
            $ketuaJurusan->jurusan_id = $jurusan->id;
            $ketuaJurusan->nip = $request->nip;
            $ketuaJurusan->nama = $request->nama;
            $ketuaJurusan->save();            
            $ketuaJurusanData = array(
                'ketuaJurusan' => $ketuaJurusan,
                'user' => $user
            );
            return $this->apiResponse(200, 'Ketua Jurusan updated', ['ketua_jurusan' => $ketuaJurusanData]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function destroyKetuaJurusan(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',            
        ]);
        try {            
            $ketuaJurusan = KetuaJurusan::findOrFail($request->id);            
            $user = $ketuaJurusan->user;
            $ketuaJurusan->delete();                        
            $user->delete();                        
            return $this->apiResponse(200, 'Ketua Jurusan deleted', null);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getAllPembantuDirektur3()
    {
        try {                        
            return $this->apiResponse(200, 'List Ketua Jurusan', ['pembantu_direktur_3' => PembantuDirektur3::with('user')->get()]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getPembantuDirektur3(Request $request)
    {
        $this->validate($request, [            
            'id' => 'required|integer',            
        ]);
        try {                        
            return $this->apiResponse(200, 'Ketua Jurusan', ['pembantu_direktur_3' => PembantuDirektur3::where('id', $request->id)->with('user')->firstOrFail()]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function storePembantuDirektur3(Request $request)
    {
        $this->validate($request, [
            'nip' => 'required|string|max:20',
            'nama' => 'required|string',
        ]);
        try {                        
            $pd3Role = Role::where('name', 'pd3')->firstOrFail();
            $user = new User;
            $user->username = $request->nip;
            $user->password = app('hash')->make($request->nip);
            $user->save();
            $user->roles()->attach($pd3Role->id);
            $pd3 = new PembantuDirektur3;
            $pd3->user_id = $user->id;
            $pd3->nip = $request->nip;
            $pd3->nama = $request->nama;
            $pd3->save();
            $pd3Data = array(
                'pd3' => $pd3,
                'user' => $user
            );
            return $this->apiResponse(200, 'Pembantu Direktur 3 created', ['pembantu_direktur_3' => $pd3Data]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function updatePembantuDirektur3(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
            'nip' => 'required|string|max:20',
            'nama' => 'required|string',
        ]);
        try {                                    
            $pd3 = PembantuDirektur3::findOrFail($request->id);            
            $user = $pd3->user;
            $user->username = $request->nip;
            $user->password = app('hash')->make($request->nip);
            $user->save();            
            $pd3->nip = $request->nip;
            $pd3->nama = $request->nama;
            $pd3->save();
            $pd3Data = array(
                'pd3' => $pd3,
                'user' => $user
            );
            return $this->apiResponse(200, 'Pembantu Direktur 3 updated', ['pembantu_direktur_3' => $pd3Data]);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function destroyPembantuDirektur3(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer'            
        ]);
        try {                                    
            $pd3 = PembantuDirektur3::findOrFail($request->id);                        
            $user = $pd3->user;
            $pd3->delete();            
            $user->delete();
            return $this->apiResponse(200, 'Pembantu Direktur 3 deleted', null);
        } catch (\Exception $e) {            
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }
}