<?php
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/gtfw_group/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppGroup.class.php';
class ProcessGroup {
   
   var $_POST;
   var $groupObj;
   var $pageView;
   var $pageInput;
   //css hanya dipake di view
	var $cssDone = 'success';
   var $cssFail = 'danger';
   var $cssAlert = 'warning';

	var $return;
	var $decId;
	var $encId;
	
	var $applicationId;
   
   function __construct () {
      $this->groupObj = new AppGroup();
      $this->_POST = $_POST->AsArray();
      $this->decId = Dispatcher::Instance()->Decrypt($_REQUEST['grp']);
      $this->encId = Dispatcher::Instance()->Encrypt($this->decId);
      $this->pageView = Dispatcher::Instance()->GetUrl('gtfw_group', 'group', 'view', 'html');
      $this->pageInput = Dispatcher::Instance()->GetUrl('gtfw_group', 'inputGroup', 'view', 'html');
		$this->applicationId = Configuration::Instance()->GetValue( 'application', 'application_id');
   }
   
   function IsEmpty($formName, $label, $sub_modul) {
      if (isset($_POST['btnsimpan'])) {
         if(trim($this->_POST[$formName]) == "") {
            $this->SendAlert("Isian $label tidak boleh kosong.", $sub_modul);
            return true;
         } else {
            return false;
         }
      }
   }
  
   function SendAlert($alert, $sub_modul, $css='') {
      Messenger::Instance()->Send('gtfw_group', $sub_modul, 'view', 'html', array($this->_POST, $alert, $css),Messenger::NextRequest);
   }
   
   function Add() {
      $cek_group = $this->IsEmpty('groupname', 'Nama Group', 'inputGroup');
      $cek_unit = $this->IsEmpty('unit_kerja', 'Unit Kerja', 'inputGroup');
      if ($cek_group || $cek_unit) {
         return $this->pageInput;
      }elseif (isset($_POST['btnbatal'])) {
         return $this->pageView;
      }elseif (isset($_POST['btnsimpan'])) {
         $arrMenu = array();
         if ($_POST['groupname']!="") {
            $menu = array();
            $addMenu = true;
            if (isset($_POST['menu'] )) {
               $addMenu = false;
               foreach($_POST['menu'] as $value) {
                  $menu[] =  $value ;
               }
               $dataMenu = $this->groupObj->GetPrivilegeByArrayId($menu);
               $len = sizeof($dataMenu);

               for ($i=0; $i<$len; $i++) {
                  if (!isset($arrMenu[$dataMenu[$i]['menu_parent_id']]['parent'])) {
                     $tmp = $this->groupObj->GetPrivilegeById($dataMenu[$i]['menu_parent_id']);
                     $arrMenu[$dataMenu[$i]['menu_parent_id']]['parent'] = $tmp[0];
                  }
                  $arrMenu[$dataMenu[$i]['menu_parent_id']]['child'][] = $dataMenu[$i];
               } 
            }
            
            $this->groupObj->StartTrans();
         
            $addGroup = $this->groupObj->DoAddGroup($this->applicationId,$_POST['groupname'], $_POST['description'], $_POST['unit_kerja']);         
            $addModuleHome = $this->groupObj->DoAddGroupModule2('', 757, true);
            $addModulePesan = $this->groupObj->DoAddGroupModuleByModuleName('collaboration');
            
			$hasil = $addGroup && $addModuleHome && $addModulePesan;
			
         	#input module berdasarkan aksi
            if (isset($_POST['aksi'])){
            	foreach($_POST['aksi'] as $arrModule){
            		$moduleId = explode('|',$arrModule);
            		foreach($moduleId as $mId){
            			$addModuleAction = $this->groupObj->DoAddGroupModule2('',$mId,true);
            			if (!$addModuleAction) break;
            		}   
            	}
				$hasil = $hasil && @$addModuleAction;
            }
            
            if ($hasil) {
               if (!empty($arrMenu)) {
                  foreach($arrMenu as $key=>$value) {
                     //add ParentMenu                    
                     $addMenu = $this->groupObj->DoAddGroupMenuForNewGroup($value['parent']['menu_name'], 
                        $value['parent']['default_module_id'], 0, $value['parent']['is_show'], $value['parent']['menu_id']);
                     $parentId = $this->groupObj->GetMaxMenuId();
                     if ($addMenu && $parentId) {
                        // add anak2nya
                        $len= sizeof($value['child']) ;
                        for ($i=0; $i<$len; $i++) {
                           $addMenu = $this->groupObj->DoAddGroupMenuForNewGroup($value['child'][$i]['menu_name'], 
                              $value['child'][$i]['default_module_id'], $parentId, $value['child'][$i]['is_show'], $value['child'][$i]['menu_id']);
                           $addModule = $this->groupObj->DoAddGroupModuleFromGtfwMenu($value['child'][$i]['menu_id'],'',$_POST['settingAksi']);
                           $addMenu = $addMenu && $addModule;
                           if (!$addMenu) {
                              break;
                              break;
                           }
                        }
                     } else {
                        break;
                     }
                  }
               }
            }
            
            $result = $addGroup && $addModuleHome && $addModulePesan && $addMenu;
            
            $this->groupObj->EndTrans($result);
            
            if ($result == true) {
               $this->SendAlert('Penambahan data Berhasil Dilakukan', 'group', $this->cssDone);
   			} else {
   			   $this->SendAlert('Gagal Menambah Data', 'group', $this->cssFail);
   			}
         }
         return $this->pageView;
      }
   }
   
   function Update() {
      $idDec = $this->decId;
      $cek_group = $this->IsEmpty('groupname', 'Nama Group', 'inputGroup');
      $cek_unit = $this->IsEmpty('unit_kerja', 'Unit Kerja', 'inputGroup');
      if ($cek_group || $cek_unit) {
         return $this->pageInput;
      }elseif (isset($_POST['btnbatal'])) {
         return $this->pageView;
      }elseif (isset($_POST['btnsimpan'])) {
         if ($_POST['groupname']!="") {
            $updateMenu = true;
            if (isset($_POST['menu'] )) {
               $updateMenu = false;
               foreach($_POST['menu'] as $value) {
                  $menu[] =  $value ;
               }
					
               $dataMenu = $this->groupObj->GetPrivilegeByArrayId($menu);
					
               $len = sizeof($dataMenu);
               
               for ($i=0; $i<$len; $i++) {
                  if (!isset($arrMenu[$dataMenu[$i]['menu_parent_id']]['parent'])) {
                     $tmp = $this->groupObj->GetPrivilegeById($dataMenu[$i]['menu_parent_id']);
                     $arrMenu[$dataMenu[$i]['menu_parent_id']]['parent'] = $tmp[0];
                  }
                  $arrMenu[$dataMenu[$i]['menu_parent_id']]['child'][] = $dataMenu[$i];
               } 
            }

            $this->groupObj->StartTrans(); 
            $updateGroup = $this->groupObj->DoUpdateGroup($this->applicationId, $_POST['groupname'], $_POST['description'],$_POST['unit_kerja'], $idDec);
            $updateMenu = false;
            $deleteMenu = $this->groupObj->DoDeleteGroupMenu($idDec);
            $deleteModule = $this->groupObj->DoDeleteGroupModule($idDec);      
            $updateDelete = $updateGroup && $deleteMenu && $deleteModule;
            $addModuleHome = $this->groupObj->DoAddGroupModule2($idDec, '757');
            $addModulePesan = $this->groupObj->DoAddGroupModuleByModuleName('collaboration', $idDec);

            if($_POST['settingAksi'] == '1'){
         	#input module berdasarkan aksi
	            if (isset($_POST['aksi'])){
	            	foreach($_POST['aksi'] as $arrModule){
	            		$moduleId = explode('|',$arrModule);
	            		foreach($moduleId as $mId){
	            			$addModuleAction = $this->groupObj->DoAddGroupModule2($idDec,$mId);
	            			if (!$addModuleAction) break;
	            		}
	            	}
	            }
            }
            
            if ($updateDelete) {
               if (!empty($arrMenu)) {
                  foreach($arrMenu as $key=>$value) {
                     //add ParentMenu
                     $addMenu = $this->groupObj->DoAddGroupMenu($value['parent']['menu_name'], $idDec, 
                        $value['parent']['default_module_id'], 0, $value['parent']['is_show'], $value['parent']['menu_id']);
                     $parentId = $this->groupObj->GetMaxMenuId();

                     if ($addMenu && $parentId) {
                        // add anak2nya
                        $len = sizeof($value['child']) ;
								
                        for ($i=0; $i<$len; $i++) {
                           $addMenu = $this->groupObj->DoAddGroupMenu($value['child'][$i]['menu_name'], $idDec,
                              $value['child'][$i]['default_module_id'], $parentId, $value['child'][$i]['is_show'], $value['child'][$i]['menu_id']);
                           $addModule = $this->groupObj->DoAddGroupModuleFromGtfwMenu($value['child'][$i]['menu_id'], $idDec,$_POST['settingAksi']);
                           $updateMenu = $addMenu && $addModule;

                           if (!$updateMenu) {
                              break;
                              break;
                           }
                        }              
                     } else {
                        break;
                     } 
                  }
               }

               $result = $updateDelete && $addModuleHome && $addModulePesan && $updateMenu;
               /**
               var_dump($updateDelete);
               var_dump($addModuleHome);
               var_dump($addModulePesan);
               var_dump($updateMenu);
               var_dump($add_akses_data);
               exit;
               /**/
               $this->groupObj->EndTrans($result); 
            } 
         if ($result === true) {
            $this->SendAlert('Pengubahan data Berhasil Dilakukan', 'group', $this->cssDone);
			} else {
			   $this->SendAlert('Gagal Mengubah Data', 'group', $this->cssFail);
			}
      }
      return $this->pageView;
      }
   }
   
   function Delete() {
      $idDec = Dispatcher::Instance()->Decrypt($_REQUEST['idDelete']);
      $label_sukses = 'Penghapusan Data Berhasil Dilakukan.';
      $label_gagal = 'Data Tidak Dapat Dihapus.';
      
      $this->groupObj->StartTrans();
      
      $deleteData = $this->groupObj->DoDeleteGroup($idDec);
      $res_delete = $deleteData;
      $this->groupObj->EndTrans($res_delete);
      
      if ($res_delete === true) {
         $this->SendAlert($label_sukses, 'group', $this->cssDone);
      } else {
         $this->SendAlert($label_gagal, 'group', $this->cssDone);
      }
      return $this->pageView;
   }
}
?>
