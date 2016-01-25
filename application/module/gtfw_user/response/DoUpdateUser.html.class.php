<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/gtfw_user/response/ProcessUser.proc.class.php';

class DoUpdateUser extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {

      $userObj = new ProcessUser();
      
      $urlRedirect = $userObj->Update();
            
      $this->RedirectTo($urlRedirect) ;
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
