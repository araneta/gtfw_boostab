<?php

class Mahasiswa extends Database
{
	protected $mSqlFile;
	
	function __construct ($connectionNumber=0) {
		$this->mSqlFile = 'module/latihan_delapan/business/mysqlt/mhs.sql.php';
			parent::__construct($connectionNumber);
	}
	
	function GetListMahasiswa($startRec, $itemViewed) {
		$result = $this->Open($this->mSqlQueries['get_list_mahasiswa'], array($startRec, $itemViewed));
		return $result;
	}
	
	function GetCountMahasiswa() {
		$result = $this->Open($this->mSqlQueries['get_count_mahasiswa'], array());
		return $result[0]['total'];
	}
}
?>