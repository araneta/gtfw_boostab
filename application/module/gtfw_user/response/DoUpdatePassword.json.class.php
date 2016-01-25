<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/gtfw_user/response/ProcessUser.proc.class.php';

class DoUpdatePassword extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {      
      $userObj = new ProcessUser();
      
      $urlRedirect = $userObj->UpdatePassword();
      
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")') ;
               
      return NULL;
   }

   function ParseTemplate($data = NULL) {
   }
}
?>
