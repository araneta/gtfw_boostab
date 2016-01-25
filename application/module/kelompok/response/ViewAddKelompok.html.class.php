<?php

$db_type       = Configuration::Instance()->GetValue(
   'application',
   'db_conn',
   0,
   'db_type'
);
require_once GTFWConfiguration::GetValue('application','docroot').
'module/'.Dispatcher::Instance()->mModule.'/business/'.$db_type.'/Kelompok.class.php';

class ViewAddKelompok extends HtmlResponse
{
   function TemplateModule(){
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
      'module/'.Dispatcher::Instance()->mModule.'/template/');
      $this->SetTemplateFile('view_add_kelompok.html');
   }

   function ProcessRequest(){
      $messenger     = Messenger::Instance()->Receive(__FILE__);
      $mObj          = new Kelompok();
      $message       = $style = $messengerData = NULL;
      $request_data  = array();
      if($messenger){
         $message       = $messenger[0][1];
         $style         = $messenger[0][2];
         $messengerData = $messenger[0][0];

         $request_data['id']     = $messengerData['data_id'];
         $request_data['nama']   = $messengerData['nama'];
      }

      return compact('request_data', 'message', 'style');
   }

   function ParseTemplate($data = null){
      extract($data);
      $url_action    = Dispatcher::Instance()->GetUrl(
         'kelompok',
         'AddKelompok',
         'do',
         'json'
      );

      $this->mrTemplate->AddVar('content', 'URL_ACTION', $url_action);
      $this->mrTemplate->AddVars('content', $request_data);

      if($message){
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $message);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $style);
      }
   }
}
?>