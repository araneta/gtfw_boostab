<?php
require_once GTFWConfiguration::GetValue('application', 'docroot').'module/latihan_sepuluh/response/ProcessMahasiswa.proc.class.php';

class DoUpdateMahasiswa extends JsonResponse {
	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$Obj = new ProcessMahasiswa();
		$urlRedirect = $Obj->Update();
		return array('exec'=> 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	}
	
	function ParseTemplate($data = NULL) {
	}
}
?>