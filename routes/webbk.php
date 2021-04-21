$router->group(['prefix' => 'beasiswa'], function () use ($router) {
		$router->post('/pendaftaran', [
			'as' => 'api.beasiswa.pendaftaran', 'uses' => 'MahasiswaController@daftarBeasiswa'
		]);

		$router->get('/all', [
    		'as' => 'api.beasiswa.all', 'uses' => 'BeasiswaController@getAll'
		]);

		$router->get('/active', [
    		'as' => 'api.beasiswa.active', 'uses' => 'BeasiswaController@getActiveBeasiswa'
		]);

		$router->post('/store', [
    		'as' => 'api.beasiswa.store', 'uses' => 'BeasiswaController@create'
		]);

		$router->put('/update', [
    		'as' => 'api.beasiswa.update', 'uses' => 'BeasiswaController@update'
		]);

		$router->post('/destroy', [
    		'as' => 'api.beasiswa.destroy', 'uses' => 'BeasiswaController@delete'
		]);
		
		$router->get('/', [
    		'as' => 'api.beasiswa', 'uses' => 'BeasiswaController@getOne'
		]);

		$router->group(['prefix' => 'kuota'], function () use ($router) {

			$router->put('/update', [
    			'as' => 'api.beasiswa.kuota.update', 'uses' => 'BeasiswaController@updateKuotaBeasiswa'
			]);

			$router->post('/store', [
	    		'as' => 'api.beasiswa.kuota.store', 'uses' => 'BeasiswaController@createKuotaBeasiswa'
			]);

			$router->post('/destroy', [
	    		'as' => 'api.beasiswa.kuota.destroy', 'uses' => 'BeasiswaController@deleteKuotaBeasiswa'
			]);

			$router->get('/program_studi/angkatan', [
    			'as' => 'api.beasiswa.kuota.program_studi.angkatan', 'uses' => 'BeasiswaController@getKuotaBeasiswaProgamStudiAngkatan'
			]);

			$router->get('/', [
    			'as' => 'api.beasiswa.kuota', 'uses' => 'BeasiswaController@getKuotaBeasiswa'
			]);
		});		

		$router->group(['prefix' => 'pendaftar'], function () use ($router) {
			$router->get('/', [
					'as' => 'api.beasiswa.pendaftar', 'uses' => 'PD3Controller@getPendaftar'
				]);
			$router->post('/penilaian', [
				'as' => 'api.beasiswa.pendaftar.penilaian', 'uses' => 'PD3Controller@seleksiBeasiswa'
			]);

			$router->post('/penyelesaian', [
				'as' => 'api.beasiswa.pendaftar.penyelesaian', 'uses' => 'PD3Controller@penyelesaianBeasiswa'
			]);

			$router->group(['prefix' => 'kelas'], function () use ($router) {
				$router->get('/', [
					'as' => 'api.beasiswa.pendaftar.kelas', 'uses' => 'WaliKelasController@getPendaftarKelas'
				]);
				$router->get('/sertifikat', [
					'as' => 'api.beasiswa.pendaftar.kelas.sertifikat', 'uses' => 'WaliKelasController@getSertifikatMahasiswa'
				]);

				$router->put('/penilaian', [
					'as' => 'api.beasiswa.pendaftar.kelas.penilaian', 'uses' => 'WaliKelasController@updateNilaiKelayakan'
				]);
			});

			$router->group(['prefix' => 'program_studi'], function () use ($router) {
				$router->get('/', [
					'as' => 'api.beasiswa.pendaftar.program_studi', 'uses' => 'KetuaProgramStudiController@getPendaftarProgramStudi'
				]);
				$router->post('/penilaian', [
					'as' => 'api.beasiswa.pendaftar.program_studi.penilaian', 'uses' => 'KetuaProgramStudiController@seleksiBeasiswaProgramStudi'
				]);
			});

			$router->group(['prefix' => 'jurusan'], function () use ($router) {
				$router->get('/', [
					'as' => 'api.beasiswa.pendaftar.jurusan', 'uses' => 'KetuaJurusanController@getPendaftarJurusan'
				]);
				$router->post('/penilaian', [
					'as' => 'api.beasiswa.pendaftar.jurusan.penilaian', 'uses' => 'KetuaJurusanController@seleksiBeasiswaJurusan'
				]);
			});
		});
	});