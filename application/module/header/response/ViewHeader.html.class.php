<?php
/** 
* @author Rabiul Akhirin <roby@gamatechno.com>
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/header/business/'.
   Configuration::Instance()->GetValue('application',array('db_conn',0,'db_type')).'/Header.class.php';

class ViewHeader extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').
         'module/header/template');
      $this->SetTemplateFile('view_header.html');
   }

   function ProcessRequest() {
   /*
      $data_obj = new Header();
      $data = $data_obj->GetPeriodeAktif();
      $_SESSION['periode_dt_aktif_id'] = $data[0]['periodedtId'];
      $return['data'] = $data;
      return $return;
   */
   }

   function ParseTemplate($data = NULL) {
   /*
      $this->mrTemplate->AddVar('content', 'SEMESTER', $data['data'][0]['SEMESTER']);
      $this->mrTemplate->AddVar('content', 'TAHUN_AJARAN', $data['data'][0]['TAHUN_AJARAN']);
   */
   }

}
?>
