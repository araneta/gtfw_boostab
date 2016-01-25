<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/ 

class DestroySession extends HtmlResponse {

   function TemplateModule() {
   }

   function ProcessRequest() {
      $module = Configuration::Instance()->GetValue( 'application', 'default_module');
      $submodule = Configuration::Instance()->GetValue( 'application', 'default_submodule');
      $action = Configuration::Instance()->GetValue( 'application', 'default_action');
      $type = Configuration::Instance()->GetValue( 'application', 'default_type');
      $this->RedirectTo($this->mrDispatcher->GetUrl($module, $submodule, $action, $type));
      return NULL;
   }

   function ParseTemplate($data = NULL) {
   }
}
?>