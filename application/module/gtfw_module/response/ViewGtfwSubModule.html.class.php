<?php
/** 
* @author Wahyono
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot').'module/gtfw_module/business/GtfwModule.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot').'main/function/request.php';
   
class ViewGtfwSubModule extends HtmlResponse
{
		var $Pesan;
		var $RegisterStatus;
   function TemplateModule()
   {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue('application','docroot').'module/gtfw_module/template/');
      $this->SetTemplateFile('view_gtfw_submodule.html');
   }
   
   function Register(){
		$GtfwModule = new GtfwModule();
		$this->modulName=$_GET['moduleName'];
		if ($GtfwModule->aktif=='bo'){
			$path_module=Configuration::Instance()->GetValue( 'application', 'docroot').'module/'.$this->modulName.'/response/';
			$AppId=Configuration::Instance()->GetValue( 'application', 'application_id');
		}else{
			$path_module=str_replace("bo","fo",Configuration::Instance()->GetValue( 'application', 'docroot')).'module/'.$this->modulName.'/response/';
			$AppId=Configuration::Instance()->GetValue( 'application', 'application_portal_id');
		}
		$this->RegisterStatus=false;
		$EditMenuId = '';
		$GtfwModule->StartTrans();
		$result=true;
		if (file_exists($path_module)){
			$dir = dir($path_module);
			while (false !== ($entry = $dir->read())) {
		        if (($entry!='..')&&($entry!='.')){
					$Temp=$GtfwModule->PecahFile($entry);
					$ActionList=array("Do","View","Popup","Input","Combo");
					if (in_array($Temp['Action'],$ActionList)){
						if ((isset($this->POST['btnsimpan']))&&($result===true)){
							$moduleLengkap=$this->modulName."-".$Temp['SubModuleName']."-".$Temp['Action']."-".$Temp['Type'];
							if(!empty($this->POST['Aksi-'.$moduleLengkap])){
								$labelAksi = $GtfwModule->GetLabelAksi($this->POST['Aksi-'.$moduleLengkap]);
							}
							if (!empty($this->POST[$moduleLengkap])=='on'){
								$params=array(
											'Module'=>$this->modulName->Raw(),
											'LabelModule'=>'['.$AppId.'] '.$Temp['Action'].' '.$Temp['SubModuleName'].' '.$Temp['Type'],
											'SubModule'=>$Temp['SubModuleName'],
											'Action'=>$Temp['Action'],
											'Type'=>$Temp['Type'],
											'Access'=>$this->POST['Access-'.$moduleLengkap],
											'ApplicationId'=>$AppId
										);
										
								$result=$GtfwModule->RegisterModule($params);

							if ($result===true){
								
									$ModuleRegistered[]=$GtfwModule->LastRegisterModule();
									if (!empty($this->POST['default'])) {
										if (Request::DecodeSanitization($this->POST['default'])==$moduleLengkap){
											$DefaultModuleId=$GtfwModule->GetIdModuleDefault($this->modulName->Raw(),$Temp['SubModuleName'],$Temp['Action'],$Temp['Type']);
										}
									}else{
										$DefaultModuleId=NULL;
									}
								}else{
									$ModuleRegistered=array();
								}
							}
							
							if(!empty($this->POST['Aksi-'.$moduleLengkap])){
								$DefaultModuleId2=$GtfwModule->GetIdModuleDefault($this->modulName->Raw(),$Temp['SubModuleName'],$Temp['Action'],$Temp['Type']);
								$GtfwModule->UpdateAksiId($this->POST['Aksi-'.$moduleLengkap],$labelAksi[0]['aksiLabel'],$DefaultModuleId2);
							}
							
							$GetModule=$GtfwModule->GetModuleByFile($this->modulName,$Temp['SubModuleName'],$Temp['Action'],$Temp['Type'],$AppId);
							if(!empty($GetModule[0]['MenuId'])){
								$EditMenuId=$GetModule[0]['MenuId'];
							}
							$this->POST['EditMenuId']=$EditMenuId;
							if(!empty($this->POST['default'])){
								if (Request::DecodeSanitization($this->POST['default'])==$moduleLengkap){
									$EditDefaultModuleId=$GetModule[0]['ModuleId'];
									$this->POST['EditDefaultModuleId']=$EditDefaultModuleId;
								}
							}
						}
					}
		        }
			}
			
			if ((isset($this->POST['btnsimpan']))&&($result===true)&&(!empty($ModuleRegistered))&&(!empty($this->POST['registerMenu'])=='on')){
				$this->POST['Proses']='Jika Register Module dan Registerkan Menu Baru';
				//Jika Register Module dan Registerkan Menu Baru
				$param_menu=array(
								'MenuParentId'=>$this->POST['MenuParentId'],
								'MenuName'=>$this->POST['MenuName'],
								'MenuDefaultModuleId'=>$DefaultModuleId,
								'IsShow'=>$this->POST['IsShow'],
								'IconPath'=>$this->POST['IconPath'],
								'ApplicationId'=>$AppId
							);
				$result=$GtfwModule->RegisterMenu($param_menu);
				if ($result===true){
					$MenuRegistered=$GtfwModule->LastRegisterMenu();
					for ($jjj=0; $jjj<sizeof($ModuleRegistered); $jjj++) {
						$result=$GtfwModule->UpdateModuleMenuId($MenuRegistered,$ModuleRegistered[$jjj]);
					}
				}
			}else if((isset($this->POST['btnsimpan']))&&($result===true)&&($EditMenuId!='')){
				$this->POST['Proses']='Jika Hanya Register Module sedangkan Menu Sudah Ada';
				//Jika Hanya Register Module sedangkan Menu Sudah Ada
			    if (isset($EditDefaultModuleId)!=''){
					$param_menu=array(
								'MenuParentId'=>$this->POST['MenuParentId'],
								'MenuName'=>$this->POST['MenuName'],
								'MenuDefaultModuleId'=>$EditDefaultModuleId,
								'IsShow'=>$this->POST['IsShow'],
								'IconPath'=>$this->POST['IconPath'],
								'ApplicationId'=>$AppId,
								'MenuId'=>$EditMenuId
							);
					$result=$GtfwModule->UpdateRegisterMenu($param_menu);
					$result=$GtfwModule->UpdateMenuModuleDefault($EditDefaultModuleId,$EditMenuId);
				}
				if(isset($ModuleRegistered)){
					for ($jjj=0; $jjj<sizeof($ModuleRegistered); $jjj++) {
						$result=$GtfwModule->UpdateModuleMenuId($EditMenuId,$ModuleRegistered[$jjj]);
					}
				}
			}else if((isset($this->POST['btnsimpan']))&&($EditMenuId!='')&&($EditDefaultModuleId!='')){
				$this->POST['Proses']='Jika Hanya merubah Default Menu';
				//Jika Hanya merubah Default Menu
				$param_menu=array(
								'MenuParentId'=>$this->POST['MenuParentId'],
								'MenuName'=>$this->POST['MenuName'],
								'MenuDefaultModuleId'=>$EditDefaultModuleId,
								'IsShow'=>$this->POST['IsShow'],
								'IconPath'=>$this->POST['IconPath'],
								'ApplicationId'=>$AppId,
								'MenuId'=>$EditMenuId
							);
				$result=$GtfwModule->UpdateRegisterMenu($param_menu);
				
				$result=$GtfwModule->UpdateMenuModuleDefault($EditDefaultModuleId,$EditMenuId);
			}else if ((isset($this->POST['btnsimpan']))&&($this->POST['registerMenu']=='on')){
				$this->POST['Proses']='Jika Hanya Register Menu Saja';
				//Jika Hanya Register Menu Saja
				$GetModuleLain=$GtfwModule->GetModuleByFile('home','home','view','html',$AppId);
				$param_menu=array(
								'MenuParentId'=>$this->POST['MenuParentId'],
								'MenuName'=>$this->POST['MenuName'],
								'MenuDefaultModuleId'=>$GetModuleLain[0]['ModuleId'],
								'IsShow'=>$this->POST['IsShow'],
								'IconPath'=>$this->POST['IconPath'],
								'ApplicationId'=>$AppId
							);
				$result=$GtfwModule->RegisterMenu($param_menu);
			}
		}
		$GtfwModule->EndTrans($result);
		return $result;
   }
   
   function ProcessRequest()
   {
		$GtfwModule = new GtfwModule();
		
		$cfg_hak_akses_per_aksi = Configuration::Instance()->GetValue( 'application', 'cfg_hak_akses_per_aksi');
		if ($cfg_hak_akses_per_aksi) {
			$settingShowAksesPerAksi = 1;
		} else {
			$settingShowAksesPerAksi = 0;
		}
		
		$return['setting_aksi'] = $settingShowAksesPerAksi;
		
		$this->POST = $_POST->AsArray();
	  
		$this->modulName=$_GET['moduleName'];
		
		if ($GtfwModule->aktif=='bo'){
			$path_module=Configuration::Instance()->GetValue( 'application', 'docroot').'module/'.$this->modulName.'/response/';
			$AppId=Configuration::Instance()->GetValue( 'application', 'application_id');
		}else{
			$path_module=str_replace("bo","fo",Configuration::Instance()->GetValue( 'application', 'docroot')).'module/'.$this->modulName.'/response/';
			$AppId=Configuration::Instance()->GetValue( 'application', 'application_portal_id');
		}
		
		if (isset($this->POST['btnsimpan'])){
			$result=$this->Register();
			if($result===true){
				$this->Pesan=$this->POST['btnsimpan'].' Module dan '.$this->POST['btnsimpan'].' Menu Berhasil';
				$this->css='success';
			}else{
				$this->Pesan=$this->POST['btnsimpan'].' Module dan '.$this->POST['btnsimpan'].' Menu Gagal';
				$this->css='danger';
			}
		}
		
		$i=0;
		if (file_exists($path_module)){
			$dir = dir($path_module);
			while (false !== ($entry = $dir->read())) {
		        if (($entry!='..')&&($entry!='.')){
					$Temp=$GtfwModule->PecahFile($entry);
					$ActionList=array("Do","View","Popup","Input","Combo");
					if (in_array($Temp['Action'],$ActionList)) {
						$listFile[$i]=$Temp;
						$GetModule=$GtfwModule->GetModuleByFile($this->modulName,$Temp['SubModuleName'],$Temp['Action'],$Temp['Type'],$AppId);
						if(!empty($GetModule)){
							$labelAksi = $GtfwModule->GetLabelAksi($GetModule[0]['AksiId']);
						}
						$listFile[$i]['name_checkbox']=$this->modulName."-".$Temp['SubModuleName']."-".$Temp['Action']."-".$Temp['Type'];
						If (sizeof($GetModule)==0) {
							$listFile[$i]['checkbox']='<input type=checkbox name="'.$listFile[$i]['name_checkbox'].'" checked>';
							$listFile[$i]['combobox']='<select name="Access-'.$listFile[$i]['name_checkbox'].'"><option value="Exclusive">Exclusive</option><option value="All">All</option></select>';
							$listFile[$i]['aksiLabel']='';
							$this->RegisterStatus=true;
						}else{
							$listFile[$i]['checkbox']='Sudah';
							$listFile[$i]['combobox']=$GetModule[0]['Access'];
							if(!empty($labelAksi)){
								$listFile[$i]['aksiLabel']=$labelAksi[0]['aksiLabel'];
							}else{
								$listFile[$i]['aksiLabel'] = '';
							}
							$this->MenuId=$GetModule[0]['MenuId'];
						}
					if($GetModule){	
						If (($GetModule[0]['ModuleId']==$GetModule[0]['MenuDefaultModuleId'])&&(sizeof($GetModule)>0)) 
							$listFile[$i]["default"]='checked'; 
						else 
							$listFile[$i]["default"]='';
					}
						
						$i++;
					}
		        }
			}
		}
		
		if (!empty($this->MenuId)){
			$readonly='readonly=true';
			$return['dataMenu']=$GtfwModule->GetMenuById($this->MenuId);
		}else{
			$readonly='';
			$return['dataMenu'] = null;
		}
		
		$IsShow = array(0=>array('ID'=>'Yes','NAME'=>'Yes'),1=>array('ID'=>'No','NAME'=>'No'));
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'IsShow', 
		array('IsShow',$IsShow,$return['dataMenu'][0]['IsShow'],'',' style="width:100px;" '.$readonly,'','',''), Messenger::CurrentRequest);
		
		$MenuParentId = $GtfwModule->GetParentMenu($AppId);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'MenuParentId', 
		array('MenuParentId',$MenuParentId,$return['dataMenu'][0]['MenuParentId'],'',' style="width:300px;" '.$readonly,'','',''), Messenger::CurrentRequest);
  	    
		$return['dataSheet'] = $listFile;
		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      if($this->Pesan)
      {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
     //tentukan value judul, button dll sesuai pilihan bahasa 
     
      if($data['setting_aksi'] == '1'){
      	$this->mrTemplate->SetAttribute('header_aksi', 'visibility', 'visible');
      	$this->mrTemplate->SetAttribute('show_data_aksi', 'visibility', 'visible');	
      }else{
      	$this->mrTemplate->SetAttribute('header_aksi', 'visibility', 'hidden');
      	$this->mrTemplate->SetAttribute('show_data_aksi', 'visibility', 'hidden');
      }
	 
	 if (empty($this->MenuId)){
		$this->mrTemplate->AddVar('content', 'CHECKED_REGISTER_MENU', '<input type="checkbox" name="registerMenu" checked>');
	 }else{
		$this->mrTemplate->AddVar('content', 'READONLY', 'readonly=true');
		$this->mrTemplate->AddVar('content', 'MENUNAME', $data['dataMenu'][0]['MenuName']);
		$this->mrTemplate->AddVar('content', 'ICONPATH', $data['dataMenu'][0]['IconPath']);
	 }
     
     $this->mrTemplate->AddVar('content', 'TITLE', 'REGISTER MODULE');
	 $this->mrTemplate->AddVar('content', 'MODULE', $this->modulName);
     $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Email Template Data');
     $this->mrTemplate->AddVar('content', 'LABEL_ACTION', $this->RegisterStatus ? 'Register' : 'Update Register');
	 
	 $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('gtfw_module','gtfwModule','view','html'));
	 $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('gtfw_module','gtfwSubModule','view','html').'&moduleName='.$this->modulName);
	  
	  
	  
	  if(empty($data['dataSheet'])){
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
         return NULL;
	  }else{
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
	  }
	    $i = 1;
      
      foreach ($data['dataSheet'] as $value)
      {
  	        $data = $value;
  		    $data['number'] = $i;
  		    $data['class_name'] = ($i % 2 == 0) ? '' : 'table-common-even';
  		    
  		    if($value['aksiLabel']){
  		    	$this->mrTemplate->AddVar('data_aksi', 'IS_EMPTY', 'NO');
  		    	$this->mrTemplate->AddVar('data_aksi', 'AKSILABEL', $value['aksiLabel']);
  		    }else{
  		    	$this->mrTemplate->AddVar('data_aksi', 'IS_EMPTY', 'YES');
  		    	$this->mrTemplate->AddVar('data_aksi', 'name_checkbox', $value['name_checkbox']);
  		    	$this->ShowDataAksi();
  		    }
  		    $this->mrTemplate->AddVars('data_item', $data, '');
            $this->mrTemplate->parseTemplate('data_item', 'a');
            $i++;
  	  }
   }
   
   function ShowDataAksi($sel = ''){
   	$this->mrTemplate->clearTemplate("data_aksi_item");
   	$GtfwModule = new GtfwModule();
   	$dataAksi = $GtfwModule->GetDataAksi($sel);
   	if($dataAksi){
   		foreach($dataAksi as $val){
   			$this->mrTemplate->AddVars('data_aksi_item', $val, '');
            $this->mrTemplate->parseTemplate('data_aksi_item', 'a');
   		}
   	}
   }
}
   

?>