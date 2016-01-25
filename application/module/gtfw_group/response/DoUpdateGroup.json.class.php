<?php
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/gtfw_group/response/ProcessGroup.proc.class.php';

class DoUpdateGroup extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {    
      $groupObj = new ProcessGroup();
      $urlRedirect = $groupObj->Update();
         return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")') ;               
   }

   function ParseTemplate($data = NULL) {     
   }
}
?>