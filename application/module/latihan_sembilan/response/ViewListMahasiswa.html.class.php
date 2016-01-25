<?php

require_once Configuration::Instance()->GetValue('application', 'docroot').'module/latihan_sembilan/business/'.Configuration::Instance()->GetValue('application', 'db_conn',0,'db_type').'/Mhs.class.php';

class ViewListMahasiswa extends HtmlResponse {

	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/latihan_sembilan/template');
		$this->SetTemplateFile('view_list_mahasiswa.html');
	}
	
	function ProcessRequest() {
		$msg = Messenger::Instance()->Receive(__FILE__);
		if($msg) {
			$return['pesan'] = $msg[0][1];
			$return['css'] = $msg[0][2];
		} else {
			$return['pesan'] = null;
			$return['css'] = null;
		}
		
		$Obj = new Mahasiswa();
		$return['dataMahasiswa'] = $Obj->GetListMahasiswa();
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$this->mrTemplate->Addvar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('latihan_sembilan', 'AddMahasiswa', 'View', 'html'));
		
		if($data['pesan']!="") {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
		}
		
		if(!empty($data['dataMahasiswa'])) {
			$this->mrTemplate->AddVar('data_mahasiswa', 'DATA_EMPTY', 'YES');
			foreach($data['dataMahasiswa'] as $key=>$value) {
				$no = $key + 1;
				$value['no'] = $no;
				$value['URL_UPDATE'] = Dispatcher::Instance()->GetUrl('latihan_sembilan', 'UpdateMahasiswa', 'view', 'html').'&idMahasiswa='.Dispatcher::Instance()->Encrypt($value['ID_MHS']);
				$this->mrTemplate->AddVars('data_mahasiswa_item', $value);
				$this->mrTemplate->parseTemplate('data_mahasiswa_item', 'a');
			}
		} else {
			$this->mrTemplate->AddVar('data_mahasiswa', 'DATA_EMPTY', 'NO');
		}
	}
}
?>