<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/gtfw_user/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppUser.class.php';

class ViewChangePassword extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir( Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/gtfw_user/template');
      $this->SetTemplateFile('change_password.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      if($msg){
	      $return['Pesan'] = $msg[0][1];
	      $return['Data'] = $msg[0];
      }else{
      	$return['Pesan'] = null;
      	$return['Data'] = null;
      }

      $return['usr'] = Dispatcher::Instance()->Decrypt($_REQUEST['usr']);
      return $return;
   }

   function ParseTemplate($data = NULL) {

      $this->mrTemplate->AddVar('content', 'USER_ID', $data['usr']);

      if ($data['Pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         
         $this->mrTemplate->AddVar('content', 'USER_ID', $data['Data'][0]['usr']);
      }

      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('gtfw_user', 'updatePassword', 'do', 'html') );
      
      $this->mrTemplate->addVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'user', 'view', 'html'));
   }
}
?>
