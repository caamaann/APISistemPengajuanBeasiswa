<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/tes', 'TesController@index');

$router->group(['prefix' => 'api'], function () use ($router) {
	// $router->post('register', 'AuthController@register');
	//$router->post('login', 'AuthController@login');
	//$router->post('logout', 'AuthController@logout');

	$router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('login', 'AuthController@login');
        $router->put('change_password', 'AuthController@change_password');
	});

    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->group(['prefix' => 'mahasiswa'], function () use ($router) {
            $router->get('/', 'AdminController@getMahasiswa');
            $router->post('/', 'AdminController@storeMahasiswa');
            $router->put('/', 'AdminController@updateMahasiswa');
            $router->delete('/', 'AdminController@destroyMahasiswa');
        });
        $router->group(['prefix' => 'wali_kelas'], function () use ($router) {
            $router->get('/', 'AdminController@getWaliKelas');
            $router->post('/', 'AdminController@storeWaliKelas');
            $router->put('/', 'AdminController@updateWaliKelas');
            $router->delete('/', 'AdminController@destroyWaliKelas');
        });
        $router->group(['prefix' => 'ketua_program_studi'], function () use ($router) {
            $router->get('/', 'AdminController@getKetuaProgramStudi');
            $router->post('/', 'AdminController@storeKetuaProgramStudi');
            $router->put('/', 'AdminController@updateKetuaProgramStudi');
            $router->delete('/', 'AdminController@destroyKetuaProgramStudi');
        });
        $router->group(['prefix' => 'ketua_jurusan'], function () use ($router) {
            $router->get('/', 'AdminController@getKetuaJurusan');
            $router->post('/', 'AdminController@storeKetuaJurusan');
            $router->put('/', 'AdminController@updateKetuaJurusan');
            $router->delete('/', 'AdminController@destroyKetuaJurusan');
        });
        $router->group(['prefix' => 'pembantu_direktur_3'], function () use ($router) {
            $router->get('/', 'AdminController@getPembantuDirektur3');
            $router->post('/', 'AdminController@storePembantuDirektur3');
            $router->put('/', 'AdminController@updatePembantuDirektur3');
            $router->delete('/', 'AdminController@destroyPembantuDirektur3');
        });
    });
//
//	$router->group(['prefix' => 'admin'], function () use ($router) {
//		$router->get('user/', 'AdminController@getUser');
//		$router->get('user/all', 'AdminController@getAllUser');
//		$router->group(['prefix' => 'mahasiswa'], function () use ($router) {
//			$router->post('/store', 'AdminController@storeMahasiswa');
//			$router->put('/update', 'AdminController@updateMahasiswa');
//			$router->post('/destroy', 'AdminController@destroyMahasiswa');
//			$router->get('/all', 'AdminController@getAllMahasiswa');
//			$router->get('/', 'AdminController@getMahasiswa');
//		});
//		$router->group(['prefix' => 'wali_kelas'], function () use ($router) {
//			$router->post('/store', 'AdminController@storeWaliKelas');
//			$router->put('/update', 'AdminController@updateWaliKelas');
//			$router->post('/destroy', 'AdminController@destroyWaliKelas');
//			$router->get('/all', 'AdminController@getAllWaliKelas');
//			$router->get('/', 'AdminController@getWaliKelas');
//		});
//		$router->group(['prefix' => 'ketua_program_studi'], function () use ($router) {
//			$router->post('/store', 'AdminController@storeKetuaProgramStudi');
//			$router->put('/update', 'AdminController@updateKetuaProgramStudi');
//			$router->post('/destroy', 'AdminController@destroyKetuaProgramStudi');
//			$router->get('/all', 'AdminController@getAllKetuaProgramStudi');
//			$router->get('/', 'AdminController@getKetuaProgramStudi');
//		});
//		$router->group(['prefix' => 'ketua_jurusan'], function () use ($router) {
//			$router->post('/store', 'AdminController@storeKetuaJurusan');
//			$router->put('/update', 'AdminController@updateKetuaJurusan');
//			$router->post('/destroy', 'AdminController@destroyKetuaJurusan');
//			$router->get('/all', 'AdminController@getAllKetuaJurusan');
//			$router->get('/', 'AdminController@getKetuaJurusan');
//		});
//		$router->group(['prefix' => 'pembantu_direktur_3'], function () use ($router) {
//			$router->post('/store', 'AdminController@storePembantuDirektur3');
//			$router->put('/update', 'AdminController@updatePembantuDirektur3');
//			$router->post('/destroy', 'AdminController@destroyPembantuDirektur3');
//			$router->get('/all', 'AdminController@getAllPembantuDirektur3');
//			$router->get('/', 'AdminController@getPembantuDirektur3');
//		});
//	});
//
//	$router->group(['prefix' => 'user'], function () use ($router) {
//		$router->put('/update', 'UserController@update');
//		$router->get('/profile', 'UserController@profile');
//	});
//
	$router->group(['prefix' => 'mahasiswa'], function () use ($router) {
		$router->put('/', 'MahasiswaController@update');
		$router->post('/', 'MahasiswaController@applyBeasiswa');
		$router->post('/berkas_wajib', 'MahasiswaController@storeBerkasWajibMahasiswa');
		$router->post('/sertifikat_wajib', 'MahasiswaController@storeSertifikatWajibMahasiswa');

		$router->group(['prefix' => 'sertifikat'], function () use ($router) {
			$router->group(['prefix' => 'prestasi'], function () use ($router) {
				$router->get('/all', 'MahasiswaController@getAllSertifikatPrestasiMahasiswa');
				$router->get('/', 'MahasiswaController@getSertifikatPrestasiMahasiswa');
				$router->post('/', 'MahasiswaController@storeSertifikatPrestasiMahasiswa');
				$router->put('/', 'MahasiswaController@updateSertifikatPrestasiMahasiswa');
				$router->delete('/', 'MahasiswaController@destroySertifikatPrestasiMahasiswa');
			});

			$router->group(['prefix' => 'organisasi'], function () use ($router) {
				$router->get('/all', 'MahasiswaController@getAllSertifikatOrganisasiMahasiswa');
				$router->get('/', 'MahasiswaController@getSertifikatOrganisasiMahasiswa');
				$router->post('/', 'MahasiswaController@storeSertifikatOrganisasiMahasiswa');
				$router->put('/', 'MahasiswaController@updateSertifikatOrganisasiMahasiswa');
				$router->delete('/', 'MahasiswaController@destroySertifikatOrganisasiMahasiswa');
			});

		});

		$router->group(['prefix' => 'orangtua'], function () use ($router) {
			$router->post('/', 'MahasiswaController@storeOrangTua');
			$router->put('/', 'MahasiswaController@updateOrangTua');
			$router->get('/', 'MahasiswaController@getOrangTua');
		});

		$router->group(['prefix' => 'saudara'], function () use ($router) {
			$router->post('/', 'MahasiswaController@storeSaudara');
			$router->put('/', 'MahasiswaController@updateSaudara');
			$router->delete('/', 'MahasiswaController@destroySaudara');
			//$router->get('/all', 'MahasiswaController@getAllSaudara');
			$router->get('/', 'MahasiswaController@getSaudara');
		});


	});
//
//
	$router->group(['prefix' => 'wali_kelas'], function () use ($router) {
		$router->group(['prefix' => 'beasiswa'], function () use ($router) {
			$router->get('/', 'WaliKelasController@getPendaftarKelas');
			$router->put('/', 'WaliKelasController@updateNilaiKelayakan');
			$router->get('/sertifikat', 'WaliKelasController@getSertifikatMahasiswa');
		});
	});
//
//	$router->group(['prefix' => 'ketua_program_studi'], function () use ($router) {
//		$router->group(['prefix' => 'beasiswa'], function () use ($router) {
//			$router->get('/pendaftar/program_studi', 'KetuaProgramStudiController@getPendaftarProgramStudi');
//			$router->post('/seleksi/program_studi', 'KetuaProgramStudiController@seleksiBeasiswaProgramStudi');
//		});
//	});
//
//	$router->group(['prefix' => 'ketua_jurusan'], function () use ($router) {
//		$router->group(['prefix' => 'beasiswa'], function () use ($router) {
//			$router->get('/pendaftar/jurusan', 'KetuaJurusanController@getPendaftarJurusan');
//			$router->post('/seleksi/jurusan', 'KetuaJurusanController@seleksiBeasiswaJurusan');
//		});
//	});
//
//	$router->group(['prefix' => 'pembantu_direktur_3'], function () use ($router) {
//		$router->group(['prefix' => 'beasiswa'], function () use ($router) {
//			$router->get('/pendaftar', 'PD3Controller@getPendaftar');
//			$router->post('/seleksi', 'PD3Controller@seleksiBeasiswa');
//			$router->post('/penyelesaian', 'PD3Controller@penyelesaianBeasiswa');
//
//
//			$router->post('/store', 'PD3Controller@storeBeasiswa');
//			$router->put('/update', 'PD3Controller@updateBeasiswa');
//			$router->post('/destroy', 'PD3Controller@destroyBeasiswa');
//
//			$router->group(['prefix' => 'kuota'], function () use ($router) {
//				$router->post('/store', 'PD3Controller@storeKuotaBeasiswa');
//				$router->put('/update', 'PD3Controller@updateKuotaBeasiswa');
//				$router->post('/destroy', 'PD3Controller@destroyKuotaBeasiswa');
//				$router->get('/program_studi/angkatan', 'PD3Controller@getKuotaBeasiswaProgamStudiAngkatan');
//				$router->get('/', 'PD3Controller@getKuotaBeasiswa');
//			});
//		});
//	});
//
	$router->group(['prefix' => 'beasiswa'], function () use ($router) {
        $router->get('/', 'BeasiswaController@getBeasiswa');
        $router->post('/', 'BeasiswaController@storeBeasiswa');
        $router->put('/', 'BeasiswaController@updateBeasiswa');
        $router->delete('/', 'BeasiswaController@destroyBeasiswa');
	});

	$router->group(['prefix' => 'program_studi'], function () use ($router) {
		$router->get('/', 'ProgramStudiController@get');
        $router->post('/', 'ProgramStudiController@storeProgramStudi');
        $router->put('/', 'ProgramStudiController@updateProgramStudi');
        $router->delete('/', 'ProgramStudiController@destroyProgramStudi');
	});

	$router->group(['prefix' => 'jurusan'], function () use ($router) {
        $router->get('/', 'JurusanController@get');
        $router->post('/', 'JurusanController@storeJurusan');
        $router->put('/', 'JurusanController@updateJurusan');
        $router->delete('/', 'JurusanController@destroyJurusan');
	});
	
	$router->group(['prefix' => 'ahp'], function () use ($router) {
        $router->get('/', 'AHPController@get');
        $router->post('/', 'AHPController@countCR');
	});

});
