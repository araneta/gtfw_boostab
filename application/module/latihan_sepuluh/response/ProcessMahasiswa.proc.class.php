<?php

require_once GTFWConfiguration::GetValue('application', 'docroot'). 'module/latihan_sepuluh/business/mysqlt/Mhs.class.php';

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
		$this->pageView = Dispatcher::Instance()->GetUrl('latihan_sepuluh', 'ListMahasiswa', 'View', 'html');
	}
	
	function Add(){
		if(isset($this->_POST['btnsimpan'])){	
			$add = $this->Obj->DoAddMahasiswa($this->_POST['nama_mhs'], $this->_POST['alamat_mhs']);
			if($add == true) {
				Messenger::Instance()->Send('latihan_sepuluh', 'ListMahasiswa', 'view', 'html', array($this->_POST,'Penambahan data berhasil dilakukan', $this->cssDone), Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('latihan_sepuluh', 'ListMahasiswa', 'view', 'html', array($this->_POST,'Penambahan data gagal dilakukan', $this->cssFail), Messenger::NextRequest);
				}
				return $this->pageView;
			}
		}
		
		function Update(){
		//echo "<pre>";print_r($this->_POST);echo "</pre>";
		if(isset($this->_POST['btnsimpan'])){
		//echo 'sini';exit;
			$idMahasiswa = $this->_POST['id_mhs'];
			$update = $this->Obj->DoUpdateMahasiswa($this->_POST['nama_mhs'], $this->_POST['alamat_mhs'], $idMahasiswa);
			if($update == true) {
				Messenger::Instance()->Send('latihan_sepuluh', 'ListMahasiswa', 'view', 'html', array($this->_POST,'Update data berhasil dilakukan', $this->cssDone), Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('latihan_sepuluh', 'ListMahasiswa', 'view', 'html', array($this->_POST,'Update data gagal dilakukan', $this->cssFail), Messenger::NextRequest);
				}
			return $this->pageView;
		}
	}
	
	function Delete() {
		$mhsId = $this->_POST['idDelete'];
		if(isset($mhsId)) {
			$delete = $this->Obj->DoDeleteMahasiswa($mhsId);
			
			if($delete == true) {
			Messenger::Instance()->Send('latihan_sepuluh', 'ListMahasiswa', 'view', 'html', array($this->_POST,'Delete Data Berhasil Dilakukan', $this->cssDone), Messenger::NextRequest);
			} else {
			Messenger::Instance()->Send('latihan_sepuluh', 'ListMahasiswa', 'view', 'html', array($this->_POST,'Delete Data Gagal Dilakukan', $this->cssFail), Messenger::NextRequest);
			}
		}
		return $this->pageView;
	}
}
?>