<?php

class Mahasiswa extends Database
{
	protected $mSqlFile;
	
	function __construct ($connectionNumber=0) {
		$this->mSqlFile = 'module/latihan_sepuluh/business/mysqlt/mhs.sql.php';
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
	echo $idMahasiswa;
		$result = $this->Open($this->mSqlQueries['get_mahasiswa_by_id'], array($idMahasiswa));
		return $result;
	}
	
	function DoUpdateMahasiswa($nama_mhs,$alamat_mhs, $idMahasiswa) {
		$result = $this->Execute($this->mSqlQueries['do_update_mahasiswa'], array($nama_mhs,$alamat_mhs, $idMahasiswa));
		return $result;
	}
	
	function DoDeleteMahasiswa($idMahasiswa) {
		$result = $this->Execute($this->mSqlQueries['do_delete_mahasiswa'], array($idMahasiswa));
		return $result;
	}
	function countData()
    {
        $query = $this->mSqlQueries['count_data'];
        $result = $this->Open($query, array());
        return $result[0]['total'];
    }

    function getData($params)
    {
		//echo "<pre>";print_r($params);echo "</pre>";
        if (is_array($params))
            extract($params);
        $filter     = '';
        //$input      = array(Configuration::Instance()->GetValue('application', 'application_id'));
        if (!empty($name)) {
            $filter .= " WHERE m.mhsNama LIKE '%$name%'";
            //$input[] = "%$name%";
        }
        $limit = '';
        if (!empty($display)){
        	$limit = "LIMIT $start, $display";
        }
        $query = $this->mSqlQueries['get_data'];
        $query = str_replace('--filter--', $filter, $query);
        $query = str_replace('--limit--', $limit, $query);
		//echo "<pre>";print_r($query);echo "</pre>";
        $result = $this->Open($query,array());
		//echo "<pre>";print_r($result);echo "</pre>";
        return $result;
    }
}
?>