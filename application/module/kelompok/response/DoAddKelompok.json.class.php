<?php

require_once GTFWConfiguration::GetValue('application','docroot').
'module/'.Dispatcher::Instance()->mModule.'/response/ProcessKelompok.proc.class.php';

class DoAddKelompok extends JsonResponse
{
   public function ProcessRequest()
   {
      $mProcess   = new ProcessKelompok();
      $url_redirect  = $mProcess->Save();
      return array(
         'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$url_redirect.'&ascomponent=1")'
      );
   }
}

?>