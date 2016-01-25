<?php

require_once Configuration::Instance()->GetValue('application', 'docroot').'module/latihan_sepuluh/business/mysqlt/Mhs.class.php';

class ViewUpdateMahasiswa extends HtmlResponse {
	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/latihan_sepuluh/template');
		$this->SetTemplateFile('view_update_mahasiswa.html');
	}
	
	function Processrequest() {
		$ObjMahasiswa = new Mahasiswa();
		$idMahasiswa = Dispatcher::Instance()->Encrypt($_GET['idMahasiswa']->Raw());
		if(!empty($idMahasiswa)) {
			$return['dataMahasiswa'] = $ObjMahasiswa->GetMahasiswaById($idMahasiswa);
		}
		
			$return['ID_MHS'] = $idMahasiswa;
			//echo "<pre>";print_r($return);echo "</pre>";
			return $return;
	}
	
	function ParseTemplate ($data = NULL) {
	
		if(!empty($data['ID_MHS'])) {
			$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('latihan_sepuluh', 'UpdateMahasiswa', 'do', 'json'));
			$this->mrTemplate->AddVar('content', 'NAMA_MHS', $data['dataMahasiswa'][0]['NAMA_MHS']);
			$this->mrTemplate->AddVar('content', 'ALAMAT_MHS', $data['dataMahasiswa'][0]['ALAMAT_MHS']);
			$this->mrTemplate->AddVar('content', 'ID_MHS', $data['ID_MHS']);
			$this->mrTemplate->addVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'ListMahasiswa', 'view', 'html'));
		} else {
			$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('latihan_sepuluh', 'ListMahasiswa', 'do', 'json'));
		}
		
	}
}
?>