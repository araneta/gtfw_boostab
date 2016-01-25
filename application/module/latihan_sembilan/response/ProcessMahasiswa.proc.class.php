<?php

require_once GTFWConfiguration::GetValue('application', 'docroot'). 'module/latihan_sembilan/business/mysqlt/Mhs.class.php';

class ProcessMahasiswa {
var $_POST;
var $Obj;
var $pageInput;
var $pageView;
var $cssDone = "alert-success";
var $cssFail = "alert-danger";

	function __construct(){
		$this->Obj = new Mahasiswa();
		$this->_POST = $_POST->AsArray();
		$this->pageView = Dispatcher::Instance()->GetUrl('latihan_sembilan', 'ListMahasiswa', 'View', 'html');
	}
	
	function Add(){
		if(isset($this->_POST['btnsimpan'])){	
			$add = $this->Obj->DoAddMahasiswa($this->_POST['nama_mhs'], $this->_POST['alamat_mhs']);
			if($add == true) {
				Messenger::Instance()->Send('latihan_sembilan', 'ListMahasiswa', 'view', 'html', array($this->_POST,'Penambahan data berhasil dilakukan', $this->cssDone), Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('latihan_sembilan', 'ListMahasiswa', 'view', 'html', array($this->_POST,'Penambahan data gagal dilakukan', $this->cssFail), Messenger::NextRequest);
				}
				return $this->pageView;
			}
		}
		
		function Update(){
		if(isset($this->_POST['btnsimpan'])){
			$idMahasiswa = $this->_POST['id_mhs'];
			$update = $this->Obj->DoUpdateMahasiswa($this->_POST['nama_mhs'], $this->_POST['alamat_mhs'], $idMahasiswa);
			if($update == true) {
			Messenger::Instance()->Send('latihan_sembilan', 'ListMahasiswa', 'view', 'html', array($this->_POST,'Update Data Berhasil Dilakukan', $this->cssDone), Messenger::NextRequest);
			} else {
			Messenger::Instance()->Send('latihan_sembilan', 'ListMahasiswa', 'view', 'html', array($this->_POST,'Update Data Gagal Dilakukan', $this->cssFail), Messenger::NextRequest);
			}
			return $this->pageView;
		}
	}
		
}
?>