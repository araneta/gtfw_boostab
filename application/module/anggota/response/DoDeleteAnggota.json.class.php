<?php
require_once GTFWConfiguration::GetValue('application','docroot').
'module/'.Dispatcher::Instance()->mModule.'/business/ProcessAnggota.proc.class.php';

class DoDeleteAnggota extends JsonResponse
{
   public function ProcessRequest()
   {
      $mProcess      = new ProcessAnggota();
      $url_redirect  = $mProcess->Delete();

      return array(
         'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$url_redirect.'&ascomponent=1")'
      );
   }
}
?>
