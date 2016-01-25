<?php

require_once Configuration::Instance()->GetValue('application', 'docroot').'module/latihan_tujuh/business/'.Configuration::Instance()->GetValue('application', 'db_conn',0,'db_type').'/Mhs.class.php';

class ViewListMahasiswa extends HtmlResponse {

	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/latihan_tujuh/template');
		$this->SetTemplateFile('view_list_mahasiswa.html');
	}
	
	function ProcessRequest() {
		$Obj = new Mahasiswa();
		$nama='';
		if(isset($_POST['nama'])) {
			$nama = $_POST['nama'];
		}
		$urlSearch = Dispatcher::Instance()->GetUrl('latihan_tujuh', 'ListMahasiswa', 'view', 'html');
		Messenger::Instance()->SendToComponent('akd_module_filter', 'ModuleFilter', 'view', 'html', 'module_filter', array($urlSearch, $nama), Messenger::CurrentRequest);
		$return['dataMahasiswa'] = $Obj->GetListMahasiswa($nama);
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