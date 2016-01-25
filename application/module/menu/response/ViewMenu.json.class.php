<?php
/** 
* @author Dyan Galih <Dyan.Galih@gmail.com>
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/ 
	require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/'.Dispatcher::Instance()->mModule.'/business/mysqlt/Menu.class.php';
	
	class ViewMenu extends JsonResponse {
		
		function ProcessRequest() {
			$objMenu = new Menu();
			$objMenu->LoadSql('module/menu/business/mysqlt/menu.sql.php');
			$arrMenu = $objMenu->GetDhtmlxMenu();
			
			for($i=0;$i<count($arrMenu);$i++){
				$arrMenu[$i]['url'] = Dispatcher::Instance()->GetUrl($arrMenu[$i]['Module'], $arrMenu[$i]['SubModule'],$arrMenu[$i]['Action'], $arrMenu[$i]['Type']);
				unset($arrMenu[$i]['Module']); 
				unset($arrMenu[$i]['SubModule']);
				unset($arrMenu[$i]['Action']); 
				unset($arrMenu[$i]['Type']);
			}
			

			return array( "exec" => "setMenu(".json_encode($arrMenu).")");
		}

	}
?>
