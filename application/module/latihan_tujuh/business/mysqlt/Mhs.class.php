<?php

class Mahasiswa extends Database
{
	protected $mSqlFile;
	
	function __construct ($connectionNumber=0) {
		$this->mSqlFile = 'module/latihan_tujuh/business/mysqlt/mhs.sql.php';
			parent::__construct($connectionNumber);
	}
	
	function GetListMahasiswa($nama) {
		$result = $this->Open($this->mSqlQueries['get_list_mahasiswa'], array('%'.$nama.'%'));
		return $result;
	}
}
?>