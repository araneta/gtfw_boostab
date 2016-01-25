<?php 

$db_type    = Configuration::Instance()->GetValue(
   'application',
   'db_conn',
   0,
   'db_type'
);

require_once GTFWConfiguration::GetValue('application','docroot').
'module/'.Dispatcher::Instance()->mModule.'/business/'.$db_type.'/Anggota.class.php';

class ViewAnggota extends HtmlResponse
{
   function TemplateModule(){
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
      'module/'.Dispatcher::Instance()->mModule.'/template/');
      $this->SetTemplateFile('view_anggota.html');
   }

   function ProcessRequest(){
      $messenger     = Messenger::Instance()->Receive(__FILE__);
      $mObj          = new Anggota();
      $arr_kelompok  = $mObj->getKelompok();
      $request_data  = array();
      $query_string  = '';
      $message       = $style = $message = NULL;

      if(isset($mObj->_POST['btnSearch'])){
         $request_data['nama']      = $mObj->_POST['nama'];
         $request_data['kelompok']  = $mObj->_POST['kelompok'];
      }elseif(isset($mObj->_GET['search'])){
         $request_data['nama']      = Dispatcher::Instance()->Decrypt($mObj->_GET['nama']);
         $request_data['kelompok']  = Dispatcher::Instance()->Decrypt($mObj->_GET['kelompok']);
      }else{
         $request_data['nama']      = '';
         $request_data['kelompok']  = '';
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

      $offset         = 0;
      $limit          = 20;
      $page           = 0;
      if(isset($_GET['page'])){
         $page    = (string) $_GET['page']->StripHtmlTags()->SqlString()->Raw();
         $offset  = ($page - 1) * $limit;
      }
      #paging url
      $url    = Dispatcher::Instance()->GetUrl(
        Dispatcher::Instance()->mModule,
        Dispatcher::Instance()->mSubModule,
        Dispatcher::Instance()->mAction,
        Dispatcher::Instance()->mType
      ).'&search='.Dispatcher::Instance()->Encrypt(1) . '&' . $query_string;

      $destination_id   = "subcontent-element";
      $data_list        = $mObj->getDataAnggota($offset, $limit, $request_data);
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


      # Combobox
      Messenger::Instance()->SendToComponent(
         'combobox',
         'Combobox',
         'view',
         'html',
         'kelompok',
         array(
            'kelompok',
            $arr_kelompok,
            $request_data['kelompok'],
            true,
            'id="cmb_kelompok"'
         ),
         Messenger::CurrentRequest
      );

      $start      = $offset+1;
      if($messenger){
         $message    = $messenger[0][1];
         $style      = $messenger[0][2];
      }
      return compact('request_data', 'query_string', 'start', 'data_list', 'message', 'style');
   }

   function ParseTemplate($data = null){
      extract($data);
      $url_search       = Dispatcher::Instance()->GetUrl(
         'anggota',
         'Anggota',
         'view',
         'html'
      );

      $url_add          = Dispatcher::Instance()->GetUrl(
         'anggota',
         'AddAnggota',
         'view',
         'html'
      );

      $url_edit         = Dispatcher::Instance()->GetUrl(
         'anggota',
         'EditAnggota',
         'view',
         'html'
      );

      $this->mrTemplate->AddVar('content', 'URL_SEARCH', $url_search);
      $this->mrTemplate->AddVar('content', 'URL_ADD', $url_add);
      $this->mrTemplate->AddVars('content', $request_data);


      if(empty($data_list)){
         $this->mrTemplate->AddVar('data_grid', 'DATA_EMPTY', 'YES');
      }else{
         $urlAccept              = 'anggota|DeleteAnggota|do|json';
         $urlReturn              = 'anggota|Anggota|view|html';
         $label_delete           = 'Anggota';
         $message_delete         = 'Penghapusan Data ini akan menghapus Data secara permanen.';

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
            .'&dataName='.$list['nama'].' | '.$list['kelompok_nama']
            .'&message='.$message_delete;

            $list['nomor']       = $start;
            $list['url_edit']    = $url_edit.'&data_id='.Dispatcher::Instance()->Encrypt($list['id']);
            $this->mrTemplate->AddVars('data_list', $list);
            $this->mrTemplate->parseTemplate('data_list', 'a');
            $start+=1;
         }
      }

      if($message){
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $message);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $style);
      }
   }
}


 ?>