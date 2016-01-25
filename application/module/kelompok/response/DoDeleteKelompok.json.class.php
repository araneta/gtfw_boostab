<?php
require_once GTFWConfiguration::GetValue('application','docroot').
'module/'.Dispatcher::Instance()->mModule.'/response/ProcessKelompok.proc.class.php';

class DoDeleteKelompok extends JsonResponse
{
   public function ProcessRequest()
   {
      $mProcess   = new ProcessKelompok();
      $url_redirect  = $mProcess->Delete();

      return array(
         'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$url_redirect.'&ascomponent=1")'
      );
   }
}
?>
