<?php
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/gtfw_group/response/ProcessGroup.proc.class.php';

class DoDeleteGroup extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {      
      $groupObj = new ProcessGroup();
      $urlRedirect = $groupObj->Delete();
      $this->RedirectTo($urlRedirect);
      return NULL;      
   }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
