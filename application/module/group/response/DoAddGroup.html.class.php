<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/group/response/ProcessGroup.proc.class.php';

class DoAddGroup extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {      
      $groupObj = new ProcessGroup();
      $urlRedirect = $groupObj->Add();
      $this->RedirectTo($urlRedirect);
      return NULL;      
   }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
