<?php

class ViewListLatihanSatu extends HtmlResponse {

	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/latihan_satu/template');
		$this->SetTemplateFile('view_list_latihan_satu.html');
	}
	
	function ProcessRequest() {
		
	}
	
	function ParseTemplate($data=NULL) {
	
	}
}
?>