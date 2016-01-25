<?php

require_once Configuration::Instance()->GetValue('application', 'docroot').'module/latihan_lima/business/'.Configuration::Instance()->GetValue('application', 'db_conn',0,'db_type').'/Mhs.class.php';

class ViewListMahasiswa extends HtmlResponse {

	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/latihan_lima/template');
		$this->SetTemplateFile('view_list_mahasiswa.html');
	}
	
	function ProcessRequest() {
		$Obj = new Mahasiswa();
		$return['dataMahasiswa'] = $Obj->GetListMahasiswa();
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$this->mrTemplate->Addvar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('latihan_lima', 'AddMahasiswa', 'View', 'html'));
		
		if(!empty($data['dataMahasiswa'])) {
			$this->mrTemplate->AddVar('data_mahasiswa', 'DATA_EMPTY', 'YES');
			foreach($data['dataMahasiswa'] as $key=>$value) {
				$no = $key + 1;
				$value['no'] = $no;
				$this->mrTemplate->AddVars('data_mahasiswa_item', $value);
				$this->mrTemplate->parseTemplate('data_mahasiswa_item', 'a');
			}
		} else {
			$this->mrTemplate->AddVar('data_mahasiswa', 'DATA_EMPTY', 'NO');
		}
	}
}
?>