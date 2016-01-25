<?php
class ViewModuleFilter extends HtmlResponse {

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot').'module/akd_module_filter/template');
		$this->SetTemplateFile('view_module_filter.html');
	}
	/** 
	* $msg[0][0] URL form pencarian
	* $msg[0][1] value nama
	*/
	
	function ProcessRequest() {
		$msg = Messenger::Instance()->Receive(__FILE__,$this->mComponentName);
		$return['module_name'] = $msg[0][0];
		$return['value_nama'] = $msg[0][1];

		return $return;
		}
		
	function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', $data['module_name']);
		$this->mrTemplate->AddVar('content', 'NAMA', $data['value_nama']);
	}
}
?>