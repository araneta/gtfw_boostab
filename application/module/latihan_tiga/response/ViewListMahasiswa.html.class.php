<?php

require_once Configuration::Instance()->GetValue('application', 'docroot').'module/latihan_tiga/business/'.Configuration::Instance()->GetValue('application', 'db_conn',0,'db_type').'/Mhs.class.php';

class ViewListMahasiswa extends HtmlResponse {

	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/latihan_tiga/template');
		$this->SetTemplateFile('view_list_mahasiswa.html');
	}
	
	function ProcessRequest() {
		$Obj = new Mahasiswa();
		$return['dataMahasiswa'] = $Obj->GetListMahasiswa();
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		if(!empty($data['dataMahasiswa'])) {
			$no = 1;
			foreach($data['dataMahasiswa'] as $key=>$value) {
				$value['no'] = $no;
				$this->mrTemplate->AddVars('data_mahasiswa', $value);
				$this->mrTemplate->parseTemplate('data_mahasiswa', 'a');
				$no++;
			}
		}
	}
}
?>