<?php

require_once GTFWConfiguration::GetValue('application', 'docroot').'module/latihan_lima/response/ProcessMahasiswa.proc.class.php';

class DoAddMahasiswa extends JsonResponse {

	function TemplateModule() {
	}

	function ProcessRequest() {
		$Obj = new ProcessMahasiswa();
		$urlRedirect = $Obj->Add();
		return array('exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	}

	function ParseTemplate($data = NULL) {
	}
}
?>