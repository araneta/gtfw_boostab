<?php

require_once Configuration::Instance()->GetValue('application', 'docroot').'module/latihan_delapan/business/'.Configuration::Instance()->GetValue('application', 'db_conn',0,'db_type').'/Mhs.class.php';

class ViewListMahasiswa extends HtmlResponse {

	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/latihan_delapan/template');
		$this->SetTemplateFile('view_list_mahasiswa.html');
	}
	
	function ProcessRequest() {
		$Obj = new Mahasiswa();
		
		$totalData = $Obj->GetCountMahasiswa();
		$itemViewed = 5;
		$currPage = 1;
		$startRec = 0;
		
		if(isset($_GET['page'])) {
			$currPage = (string) $_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec = ($currPage - 1) * $itemViewed;
		}
		
		$return['dataMahasiswa'] = $Obj->GetListMahasiswa($startRec, $itemViewed);
		
		$url = Dispatcher::Instance()->GetUrl(
		Dispatcher::Instance()->mModule,
		Dispatcher::Instance()->mSubModule,
		Dispatcher::Instance()->mAction,
		Dispatcher::Instance()->mType);
		
		$destination_id = "subcontent-element";
		
		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed, $totalData, $url, $currPage, $destination_id), Messenger::CurrentRequest);
		$return['start_number'] = $startRec+1;
		
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		if(!empty($data['dataMahasiswa'])) {
			$no = $data['start_number'];
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