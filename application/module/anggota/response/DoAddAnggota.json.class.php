<?php

require_once GTFWConfiguration::GetValue('application','docroot').
'module/'.Dispatcher::Instance()->mModule.'/business/ProcessAnggota.proc.class.php';

class DoAddAnggota extends JsonResponse
{
   public function ProcessRequest()
   {
      $mProcess      = new ProcessAnggota();
      $url_redirect  = $mProcess->Save();

      return array(
         'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$url_redirect.'&ascomponent=1")'
      );
   }
}

?>