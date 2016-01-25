<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/ 

class DoLogout extends HtmlResponse {

   function TemplateModule() {
   }

   function ProcessRequest() {
      Security::Instance()->Logout(TRUE);
      //$this->RedirectTo($this->mrDispatcher->GetUrl('login_default', 'session', 'destroy', 'html'));
         $module = Configuration::Instance()->GetValue( 'application', 'default_module');
         $submodule = Configuration::Instance()->GetValue( 'application', 'default_submodule');
         $action = Configuration::Instance()->GetValue( 'application', 'default_action');
         $type = Configuration::Instance()->GetValue( 'application', 'default_type');
         $this->RedirectTo(Dispatcher::Instance()->GetUrl($module, $submodule, $action, $type));
   }

   function ParseTemplate($data = NULL) {
   }
}
?>
