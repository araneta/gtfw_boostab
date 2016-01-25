<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/ 

class Menu extends Database {
   
   function ListAvailableMenu($groupId, $flagShow="") {
      if ($flagShow != "") {
         $result = $this->GetAllDataAsArray($this->mSqlQueries['nav'], array($groupId, $flagShow));
      } else {
         $result = $this->GetAllDataAsArray($this->mSqlQueries['list_available_menu'], array($groupId));
      }
      
      return $result;
   }
   
   function ListAvailableSubMenu($parentId, $flagShow="") {
      if ($flagShow != "") {
         $result = $this->GetAllDataAsArray($this->mSqlQueries['list_available_submenu_with_flag_show'], array($parentId, $flagShow));
      } else {
         $result = $this->GetAllDataAsArray($this->mSqlQueries['list_available_submenu'], array($parentId));
      }
      
      return $result;
   }
   
   function ListAllAvailableSubMenuForGroup($userId,$menuId) {
	   if(empty($menuId) || $menuId == ''){
			$menuId = '0';
	   }
   	//die('tet');
		#printf($this->mSqlQueries['list_all_available_submenu_for_group'], $userId,$menuId);
      $result = $this->Open($this->mSqlQueries['list_all_available_submenu_for_group'], array($userId,$menuId));
      return $result;
   }
}
?>
