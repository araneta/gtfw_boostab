<?php

require_once GTFWConfiguration::GetValue('application', 'docroot'). 'module/latihan_lima/business/mysqlt/Mhs.class.php';

class ProcessMahasiswa {
var $_POST;
var $Obj;
var $pageInput;
var $pageView;

	function __construct(){
		$this->Obj = new Mahasiswa();
		$this->_POST = $_POST->AsArray();
		$this->pageView = Dispatcher::Instance()->GetUrl('latihan_lima', 'ListMahasiswa', 'View', 'html');
	}
	
	function Add(){
		if(isset($this->_POST['btnsimpan'])){	
			$add = $this->Obj->DoAddMahasiswa($this->_POST['nama_mhs'],$this->_POST['alamat_mhs']);
			return $this->pageView;
		}
		return $this->pageView;
	}
}
?>