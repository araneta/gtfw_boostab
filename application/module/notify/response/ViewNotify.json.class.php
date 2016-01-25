<?php
/** 
* @author Dyan Galih <Dyan.Galih@gmail.com>
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/'.Dispatcher::Instance()->mModule.'/business/Notify.class.php';

class ViewNotify extends JsonResponse {

	function ProcessRequest() {
		$objNotify = new Notify();
		
		if(isset($_GET['notifId'])){
			$arrModule = $objNotify->GetModuleFromNotify($_GET['notifId']);
			
			$urlRedirect=Dispatcher::Instance()->GetUrl($arrModule['module'], $arrModule['submodule'], $arrModule['action'], $arrModule['type']).$arrModule['notifyUrl'];
			return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
		}
		
		if(isset($_GET['readNotifyId'])){
			$objNotify->setReadNotify($_GET['readNotifyId']);
		}
		
		if(isset($_GET['statusMessage'])){
			$message = $objNotify->GetAllNotify();
		}else{
			$message = $objNotify->GetUnloadNotify();
		}
			
		
		return array( "exec" => "setNotify(".json_encode($message).")");
	}

}	
?>