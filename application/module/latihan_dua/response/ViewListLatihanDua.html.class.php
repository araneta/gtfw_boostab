<?php

class ViewListLatihanDua extends HtmlResponse {

	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/latihan_dua/template');
		$this->SetTemplateFile('view_latihan_dua.html');
	}
	
	function ProcessRequest() {
		$return['NAMA'] = 'Nanang Hibul Mizar';
		$return['EMAIL'] = 'hibulmizar@yahoo.com';
		
		return $return;
	}
	
	function ParseTemplate($data=NULL) {
		$this->mrTemplate->AddVar('info', 'NAMA', $data['NAMA']);
		$this->mrTemplate->AddVar('info', 'EMAIL', $data['EMAIL']);
	}
}
?>