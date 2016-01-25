<?php

class Mahasiswa extends Database
{
	protected $mSqlFile;
	
	function __construct ($connectionNumber=0) {
		$this->mSqlFile = 'module/latihan_sembilan/business/mysqlt/mhs.sql.php';
			parent::__construct($connectionNumber);
	}
	
	function GetListMahasiswa() {
		$result = $this->Open($this->mSqlQueries['get_list_mahasiswa'], array());
		return $result;
	}
	
	function DoAddMahasiswa($namaMahasiswa,$alamatMahasiswa) {
		$result = $this->Execute($this->mSqlQueries['do_add_mahasiswa'], array($namaMahasiswa,$alamatMahasiswa));
		return $result;
	}
		
	function GetMahasiswaById($idMahasiswa) {
		$result = $this->Open($this->mSqlQueries['get_mahasiswa_by_id'], array($idMahasiswa));
		return $result;
	}
	
	function DoUpdateMahasiswa($nama_mhs,$alamat_mhs, $idMahasiswa) {
		$result = $this->Execute($this->mSqlQueries['do_update_mahasiswa'], array($nama_mhs,$alamat_mhs, $idMahasiswa));
		return $result;
	}
}
?>