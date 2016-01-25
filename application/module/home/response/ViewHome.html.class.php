<?php
/** 
* @author Rabiul Akhirin <roby@gamatechno.com>
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/menu/business/'.Configuration::Instance()->GetValue( 'application',array('db_conn',0,'db_type')).'/Menu.class.php';

class ViewHome extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot') .
         'module/home/template');
      $this->SetTemplateFile('view_home.html');
   }

   function ProcessRequest() {
      $menuObj = new Menu();
      $menuObj->LoadSql('module/menu/business/'.Configuration::Instance()->GetValue( 'application',array('db_conn',0,'db_type')).'/menu.sql.php');
      $menu = $menuObj->ListAllAvailableSubMenuForGroup($_SESSION['username'],$_GET['dmmid']);
      //print_r($menu);exit;
      return $menu;
   }
   
   function GetMenuKey ($name, $arrMenu, $len) {
      for ($i=0; $i<$len; $i++) {
         if ($name == $arrMenu[$i]) {
            return  $i;
         }
      }
      return -1;
   }

   function ParseTemplate($data = NULL) {
      if (!empty($data)) {
         for($i=0;$i<sizeof($data);$i++){
            $url='';
            if($data[$i]['ParentMenuId']=='0'){
               $url = '&dmmid='.$data[$i]['MenuId'].'&mid='.$data[$i]['MenuId'];
					$this->mrTemplate->addVar('icon_menu', 'MOUSE_UP', 'onMouseUp="ShowMenu('.$data[$i]['MenuId'].')"');
				}
            $this->mrTemplate->addVar('icon_menu', 'LINK_URL', Dispatcher::Instance()->GetUrl($data[$i]['Module'], $data[$i]['SubModule'], $data[$i]['Action'], $data[$i]['Type']).$url);
            $this->mrTemplate->addVar('icon_menu', 'ICON_NAME', $data[$i]['IconPath']);
            $this->mrTemplate->addVar('icon_menu', 'LINK_NAME', $data[$i]['MenuName']);
				
            $this->mrTemplate->parseTemplate('icon_menu', 'a');
         }
      }
   }
}
?>
