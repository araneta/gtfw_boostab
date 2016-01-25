<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/group/response/ProcessGroup.proc.class.php';

class DoAddGroup extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {    
      $groupObj = new ProcessGroup();
      $urlRedirect = $groupObj->Add();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")') ;               
   }

   function ParseTemplate($data = NULL) {     
   }
}
?>
