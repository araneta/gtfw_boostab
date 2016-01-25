<?php
$db_type       = Configuration::Instance()->GetValue(
   'application',
   'db_conn',
   0,
   'db_type'
);
require_once GTFWConfiguration::GetValue('application','docroot').
'module/'.Dispatcher::Instance()->mModule.'/business/'.$db_type.'/Kelompok.class.php';

class ViewKelompok extends HtmlResponse {

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/'.Dispatcher::Instance()->mModule.'/template/');
      	$this->SetTemplateFile('view_kelompok.html');
	}
	
	function ProcessRequest(){
		$mObj          = new Kelompok();
		$request_data  = array();
		$query_string  = '';

		if(isset($mObj->_POST['btnSearch'])){
		 $request_data['nama']      = $mObj->_POST['nama'];
		}elseif(isset($mObj->_GET['search'])){
		 $request_data['nama']      = $mObj->_GET['nama'];
		}else{
		 $request_data['nama']      = '';
		}

		if(method_exists(Dispatcher::Instance(), 'getQueryString')){
		 # @param array
		 $query_string  = Dispatcher::instance()->getQueryString($request_data);
		}else{
		 $query         = array();
		 foreach ($request_data as $key => $value) {
		    $query[$key]   = Dispatcher::Instance()->Encrypt($value);
		 }
		 $query_string     = urldecode(http_build_query($query));
		}

		$offset        = 0;
		$limit         = 20;
		$page          = 0;
		if(isset($_GET['page'])){
		 $page       = (string) $_GET['page']->StripHtmlTags()->SqlString()->Raw();
		 $offset     = ($page - 1) * $limit;
		}

		#paging url
		$url           = Dispatcher::Instance()->GetUrl(
		Dispatcher::Instance()->mModule,
		Dispatcher::Instance()->mSubModule,
		Dispatcher::Instance()->mAction,
		Dispatcher::Instance()->mType
		).'&search='.Dispatcher::Instance()->Encrypt(1) . '&' . $query_string;

		$destination_id   = "subcontent-element";
		$data_list        = $mObj->getDataKelompok($offset, $limit, $request_data);
		$total_data       = $mObj->Count();

		#send data to pagging component
		Messenger::Instance()->SendToComponent(
		 'paging',
		 'Paging',
		 'view',
		 'html',
		 'paging_top',
		 array(
		    $limit,
		    $total_data,
		    $url,
		    $page,
		    $destination_id
		 ),
		 Messenger::CurrentRequest
		);
		$start      = $offset+1;
		return compact('request_data', 'query_string', 'start', 'data_list', 'message', 'style');
   }
	
	function ParseTemplate($data=NULL) {
		extract($data);
    	$url_search       = Dispatcher::Instance()->GetUrl(
         'kelompok',
         'Kelompok',
         'view',
         'html'
    	);

////////ADD/////////////////////////////////////////////////////////////////////
		$url_add          = Dispatcher::Instance()->GetUrl(
		   'kelompok',
		   'AddKelompok',
		   'view',
		   'html'
		);

		// parse string ke dalam template dengan nama URL_ADD
		$this->mrTemplate->AddVar('content', 'URL_ADD', $url_add);

////////EDIT/////////////////////////////////////////////////////////////////////
		$url_edit         = Dispatcher::Instance()->GetUrl(
		   'kelompok',
		   'EditKelompok',
		   'view',
		   'html'
		);

////////DELETE/////////////////////////////////////////////////////////////////////
		$urlAccept              = 'kelompok|DeleteKelompok|do|json';
		$urlReturn              = 'kelompok|Kelompok|view|html';
		$label_delete           = 'Kelompok';
		$message_delete         = 'Penghapusan Data ini akan menghapus Data secara permanen.';


      	$this->mrTemplate->AddVar('content', 'URL_SEARCH', $url_search);
      	$this->mrTemplate->AddVars('content', $request_data);

      	if(empty($data_list)){
         $this->mrTemplate->AddVar('data_grid', 'DATA_EMPTY', 'YES');
      	}else{

         $this->mrTemplate->AddVar('data_grid', 'DATA_EMPTY', 'NO');


         foreach ($data_list as $list) {
		   $list['url_delete']  = Dispatcher::Instance()->GetUrl(
		      'confirm',
		      'confirmDelete',
		      'do',
		      'html'
		   ).'&urlDelete='. $urlAccept
		   .'&urlReturn='.$urlReturn
		   .'&id='.$list['id']
		   .'&label='.$label_delete
		   .'&dataName='.$list['nama']
		   .'&message='.$message_delete;


            $list['nomor']       = $start;
           	$list['url_edit']    = $url_edit.'&data_id='.Dispatcher::Instance()->Encrypt($list['id']);

            $this->mrTemplate->AddVars('data_list', $list);
            $this->mrTemplate->parseTemplate('data_list', 'a');
            $start++;
         }
      }		
	}
}
?>