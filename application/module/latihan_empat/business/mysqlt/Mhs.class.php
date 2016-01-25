<?php

class Mahasiswa extends Database
{
	protected $mSqlFile;
	
	function __construct ($connectionNumber=0) {
		$this->mSqlFile = 'module/latihan_empat/business/mysqlt/mhs.sql.php';
			parent::__construct($connectionNumber);
	}
	
	function GetListMahasiswa() {
		$result = $this->Open($this->mSqlQueries['get_list_mahasiswa'], array());
		return $result;
	}
}
?>