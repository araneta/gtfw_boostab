<?php
/** 
* @author Rabiul Akhirin <roby@gamatechno.com>
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class ViewNavigation extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').
         'module/navigation/template');
      $this->SetTemplateFile('view_navigation.html');
   }

   function ProcessRequest() {
   }

   function ParseTemplate($data = NULL) {
   }

}
?>
