<?php 

$db_type    = Configuration::Instance()->GetValue(
   'application',
   'db_conn',
   0,
   'db_type'
);

require_once GTFWConfiguration::GetValue('application','docroot').
'module/'.Dispatcher::Instance()->mModule.'/business/'.$db_type.'/Anggota.class.php';

class ViewEditAnggota extends HtmlResponse
{
   function TemplateModule(){
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
      'module/'.Dispatcher::Instance()->mModule.'/template/');
      $this->SetTemplateFile('view_edit_anggota.html');
   }

   function ProcessRequest(){
      $mObj          = new Anggota();
      $messenger     = Messenger::Instance()->Receive(__FILE__);
      $request_data  = array();
      $arr_kelompok  = $mObj->getKelompok();
      $message       = $style = $messengerData = NULL;
      $data_id       = Dispatcher::Instance()->Decrypt($mObj->_GET['data_id']);
      $data_anggota  = $mObj->getDetilAnggota($data_id);

      $request_data['kelompok']  = $data_anggota['kelompok_id'];
      $request_data['id']        = $data_anggota['id'];
      $request_data['nama']      = $data_anggota['nama'];
      $request_data['email']     = $data_anggota['email'];
      $request_data['phone']     = $data_anggota['phone'];
      $request_data['alamat']    = $data_anggota['address'];

      if($messenger){
         $message    = $messenger[0][1];
         $style      = $messenger[0][2];
         $messengerData    = $messenger[0][0];
         $request_data['id']           = $messengerData['data_id'];
         $request_data['kelompok']     = $messengerData['kelompok'];
         $request_data['nama']         = $messengerData['nama'];
         $request_data['email']        = $messengerData['email'];
         $request_data['phone']        = $messengerData['phone'];
         $request_data['alamat']       = $messengerData['alamat'];
      }

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
            false,
            'id="cmb_kelompok"'
         ),
         Messenger::CurrentRequest
      );

      return compact('request_data', 'message', 'style');
   }

   function ParseTemplate($data = null){
      extract($data);
      $url_action       = Dispatcher::Instance()->GetUrl(
         'anggota',
         'UpdateAnggota',
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