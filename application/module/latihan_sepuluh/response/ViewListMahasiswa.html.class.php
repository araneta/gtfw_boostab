<?php
/**
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
*/
require_once Configuration::Instance()->GetValue('application','docroot').'module/latihan_sepuluh/business/mysqlt/Mhs.class.php';

class ViewListMahasiswa extends HtmlResponse{
	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/latihan_sepuluh/template');
		$this->SetTemplateFile('view_list_mahasiswa.html');
	}
	
	function ProcessRequest() {
		$msg = Messenger::Instance()->Receive(__FILE__);
		$Obj = new Mahasiswa();
		$filter_data = !empty($msg[0][0])? $msg[0][0]:NULL;
    	$message['content'] = !empty($msg[0][1])?$msg[0][1]:NULL;
    	$message['style'] = !empty($msg[0][2])?$msg[0][2]:NULL;
        $view_per_page = Configuration::Instance()->GetValue('application', 'paging_limit');
    	$view_per_page = 10;
    	if (!isset($_GET['display']) || empty($filter_data)) {
    	    $page = 1;
    	    $start = 0;
    	    $display = $view_per_page;
    	    $filter = compact('page', 'display', 'start');
    	} elseif ($_GET['display']->Raw() != '') {
    	    $page = (int)$_GET['page']->SqlString()->Raw();
    	    $display = (int)$_GET['display']->SqlString()->Raw();
    	
    	    if ($page < 1)
    	        $page = 1;
    	    if ($display < 1)
    	        $display = $view_per_page;
    	    $start = ($page - 1) * $display;
    	
    	    $filter = compact('page', 'display', 'start');
    	    $filter += $filter_data;
    	} else {
    	    $filter = $filter_data;
    	    $page = $filter['page'];
    	    $display = $filter['display'];
    	    $start = $filter['start'];
    	}
    	
    	$post_data = $_POST->AsArray();
    	if (!empty($post_data)) {
    	    foreach ($post_data as $key => $value)
    	        $filter[$key] = $value;
    	}
    	Messenger::Instance()->Send(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType, array($filter), Messenger::UntilFetched);
    	        
        $data   = $Obj->getData($filter);
        $total  = $Obj->countData();
    	
    	$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType).'&display='.$view_per_page;
    	Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($display, $total, $url, $page), Messenger::CurrentRequest);
		return compact('data', 'filter', 'message');
}

	function ParseTemplate($data=NULL){
		$this->mrTemplate->Addvar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('latihan_sepuluh', 'AddMahasiswa', 'View', 'html'));
			if (is_array($data))
            extract($data);
        	if (!empty($message)) {
			$this->mrTemplate->SetAttribute('message', 'visibility', 'visible');
            $this->mrTemplate->addVars('message', $message);
        	}
        $this->mrTemplate->addVar('search', 'URL', Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType));
        if (!empty($filter)) {
            $this->mrTemplate->addVars('search', $filter);
        }

        if (!empty($data)) {
            $this->mrTemplate->addVar('data', 'IS_EMPTY', 'NO');
            $no = $filter['start'] + 1;
            foreach ($data as $val) {
                $val['no'] = $no;
                $val['row_class'] = $no%2 == 0?'even':'odd';
				$val['URL_UPDATE'] = Dispatcher::Instance()->GetUrl('latihan_sepuluh', 'UpdateMahasiswa', 'view', 'html').'&idMahasiswa='.Dispatcher::Instance()->Encrypt($val['ID_MHS']);
				//delete
				$label = "Mahasiswa";
				$idEnc = Dispatcher::Instance()->Encrypt($val['ID_MHS']);
				$dataName = Dispatcher::Instance()->Encrypt($val['NAMA_MHS']);
				$urlAccept = 'latihan_sepuluh|DeleteMahasiswa|do|json';
				$urlReturn = 'latihan_sepuluh|ListMahasiswa|view|html';
				$val['URL_DELETE']=Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='.$urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
				$this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
                $this->mrTemplate->addVars('item', $val);
                $this->mrTemplate->parseTemplate('item', 'a');
                $no++;
            }
        } else {
            $this->mrTemplate->addVar('data', 'IS_EMPTY', 'YES');
        }
	}
}
?>
