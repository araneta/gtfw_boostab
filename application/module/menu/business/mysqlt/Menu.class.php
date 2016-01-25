<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/ 

class Menu extends Database {
   public function __construct($connectionnumber=0){
      parent::__construct($connectionnumber);
      $this->LoadSql('module/menu/business/mysqlt/menu.sql.php');
      $this->SetDebugOn();
   }
   
   function ListAvailableMenu($groupId, $flagShow="") {
      
      if ($flagShow != "") {
         $result = $this->GetAllDataAsArray($this->mSqlQueries['nav'], array($groupId, $flagShow));
      } else {
         $result = $this->GetAllDataAsArray($this->mSqlQueries['list_available_menu'], array($groupId));

      }
      // echo $this->getlasterror();
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
   	//die('tet');
		#printf($this->mSqlQueries['list_all_available_submenu_for_group'], $userId,$menuId);
      $result = $this->Open($this->mSqlQueries['list_all_available_submenu_for_group'], array($userId,$menuId));
      return $result;
   }

   /* oleh oleh berangkat 200k
      tiket pp 1300k
      sisa untuk akomodasi di tkp dan oleh oleh 500k

      rincian tiket pesawat:
      berangkat -> garuda -> total 1.910.400 -> 636.800/orang
      pulang -> lion -> total 1.944.300 -> 648.100/orang

      rincian masing-masing biaya:
      masari -> setor 1.300.000 (+ 15.100)
      cakdinding -> setor 1.200.000 (- 84.900)
   */

   #gtfwMethodOpen
   public function GetDhtmlxMenu(){
   	$username = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserName();
   	$result = $this->Open($this->mSqlQueries['dhtmlx_menu'], array($username));
   	return $result;
   }
}
?>
