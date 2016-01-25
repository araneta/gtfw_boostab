<?php
/** 
* @author Wahyono
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot').'module/gtfw_module/business/GtfwModule.class.php';
   
class ViewGtfwModule extends HtmlResponse
{
	var $Pesan;
   function TemplateModule()
   {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue('application','docroot').'module/gtfw_module/template/');
      $this->SetTemplateFile('view_gtfw_module.html');
   }
   
   function ProcessRequest()
   {
      $GtfwModule = new GtfwModule();
	  $this->aktif=$GtfwModule->aktif;
	  if ($GtfwModule->aktif=='bo'){
		$path_module=Configuration::Instance()->GetValue( 'application', 'docroot').'module/';
		$AppId=Configuration::Instance()->GetValue( 'application', 'application_id');
	  }else{
		$path_module=str_replace("bo","fo",Configuration::Instance()->GetValue( 'application', 'docroot')).'module/';
		$AppId=Configuration::Instance()->GetValue( 'application', 'application_portal_id');
	  }
  	  
  	  $dir = dir($path_module);
	  $i=0;
      while (false !== ($entry = $dir->read())) {
        if (($entry!='..')&&($entry!='.')){
            
			$path_submodule=$path_module.$entry.'/response/';
			$j=0; $jj=0;
			if (file_exists($path_submodule)){
				$dir2 = dir($path_submodule);
				while (false !== ($entry2 = $dir2->read())) {
					if (($entry2!='..')&&($entry2!='.')){
					    $Temp=$GtfwModule->PecahFile($entry2);
						$ActionList=array("Do","View","Popup","Input","Combo");
						if (in_array($Temp['Action'],$ActionList)) $j++;
						
						$GetModule=$GtfwModule->GetModuleByFile($entry,$Temp['SubModuleName'],$Temp['Action'],$Temp['Type'],$AppId);
						If (sizeof($GetModule)>0) $jj++;
					}
				}
			}
			
			$listFile[$i]['module']=$entry;
			$listFile[$i]['jumlah_submodule']=$j;
			$listFile[$i]['terdaftar']=$jj;
			$listFile[$i]['belum_terdaftar']=$j-$jj;
			$i++;
        }
      }
  	  
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
     
     $this->mrTemplate->AddVar('content', 'TITLE', 'REGISTER MODULE');
     $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Email Template Data');
	 $this->mrTemplate->AddVar('content', strtoupper($this->aktif).'_SELECTED', 'selected');
     $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
      
	  
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
  		     $data['url_detail'] = Dispatcher::Instance()->GetUrl('gtfw_module','gtfwSubModule','view','html')."&moduleName=".$data['module']."";
  		     $this->mrTemplate->AddVars('data_item', $data, '');
           $this->mrTemplate->parseTemplate('data_item', 'a');
           $i++;
  	  }
   }
}
   

?>