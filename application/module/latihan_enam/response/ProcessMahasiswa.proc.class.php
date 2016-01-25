<?php

require_once GTFWConfiguration::GetValue('application', 'docroot'). 'module/latihan_enam/business/mysqlt/Mhs.class.php';

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
		$this->pageView = Dispatcher::Instance()->GetUrl('latihan_enam', 'ListMahasiswa', 'View', 'html');
	}
	
	function Add(){
		if(isset($this->_POST['btnsimpan'])){	
			$add = $this->Obj->DoAddMahasiswa($this->_POST['nama_mhs'],$this->_POST['alamat_mhs']);
			if($add == true) {
				Messenger::Instance()->Send('latihan_enam', 'ListMahasiswa', 'view', 'html', array($this->_POST,'Penambahan data berhasil dilakukan', $this->cssDone), Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('latihan_enam', 'ListMahasiswa', 'view', 'html', array($this->_POST,'Penambahan data gagal dilakukan', $this->cssFail), Messenger::NextRequest);
			}
			return $this->pageView;
		}
	}
}
?>