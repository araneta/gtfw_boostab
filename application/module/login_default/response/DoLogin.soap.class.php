<?php
/** 
* @author Akhmad Fathonih <toni@gamatechno.com>
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/ 

require_once Configuration::Instance()->GetValue( 'application', 'gtfw_base').'main/lib/sobb/SoapGatewayBase.class.php';

require_once Configuration::Instance()->GetValue( 'application', 'docroot').'module/login_default/business/login.service.class.php';


class DoLogin extends SoapGatewayBase {
   function __construct() {
      parent::__construct();
   }

   /**
   Overide this method to set WSDL
   */
   function configureWsdlEvent() {

      $this->configureWsdl('LoginService', false, Configuration::Instance()->GetValue( 'application', 'baseaddress').Dispatcher::Instance()->GetWsdlUrl('login_default', 'Login', 'Do', 'soap'));
   }

   /**
   * register service object here
   */
   function collectServiceObjects() {
      $objDefault = new LoginService();
      $this->importServices($objDefault);
   }
}

?>
