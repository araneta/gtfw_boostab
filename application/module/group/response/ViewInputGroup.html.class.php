<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/group/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppGroup.class.php';

class ViewInputGroup extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').'module/group/template');
      $this->SetTemplateFile('input_group.html');
   }
   
   function ProcessRequest() {
		$msg = Messenger::Instance()->Receive(__FILE__);
		@$return['Pesan'] = $msg[0][1];
		@$return['Data'] = $msg[0][0];
		
		$applicationId = Configuration::Instance()->GetValue( 'application', 'application_id');

		$idDec = Dispatcher::Instance()->Decrypt($_REQUEST['grp']);
		if ($idDec == '')
         @$idDec = Dispatcher::Instance()->Decrypt($return['Data']['0']['grp']);
			
      $groupObj = new AppGroup();
      $menuGroup = $groupObj->GetAllPrivilege($idDec, $applicationId);      
      $return['menuGroup'] = $menuGroup;

      $dataGroup = $groupObj->GetDataGroupById($idDec);
      $dataUnitKerja = $groupObj->GetComboUnitKerja($applicationId);
      
      #action
      $actionTemp = $groupObj->GetAllAksiByGroupId($idDec);
      foreach($actionTemp as $values){
      	$key = $values['MenuId'];
      	$actionGroup[$key][] = $values;
      }
      //print_r($actionGroup);
      //exit;
      $return['actionGroup'] = $actionGroup;

      if (isset($dataGroup['0']['group_unit_id']))
         $unit_selected = $dataGroup['0']['group_unit_id'];
      else
         @$unit_selected = $return['Data']['0']['unit_kerja'];
      
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit_kerja', 
         array('unit_kerja',$dataUnitKerja,$unit_selected,'false'), Messenger::CurrentRequest);

		$return['decDataId'] = $idDec;
		$return['dataGroup'] = $dataGroup;
		return $return;
	}

   function ParseTemplate($data = NULL) {
		$dataGroup = $data['dataGroup'];
      if ($data['Pesan']) {
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      }
        
      if ($_REQUEST['grp']=='') {
         $url="addGroup";
         $tambah="Tambah";
      } else {
         $url="updateGroup";
         $tambah="Ubah";
      }       
      
      #print_r($data['dataGroup']);
      $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
      $this->mrTemplate->AddVar('content', 'GROUPNAME', empty($dataGroup[0]['groupname'])?$data['Data']['groupname']:$dataGroup[0]['groupname']);
      $this->mrTemplate->AddVar('content', 'DESKRIPSI', empty($dataGroup[0]['description'])?$data['Data']['description']:$dataGroup[0]['description']);
      $this->mrTemplate->AddVar('content', 'GRP', $_REQUEST['grp']);
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('group', $url, 'do', 'html'));
      @$menu = explode('|',$dataGroup[0]['menu_name']);
      $menuGroup = $data['menuGroup'];
      
      $len = sizeOf($menuGroup);
      $mlen = sizeOf($menu);
      for ($i=0;$i<$len;$i++) {  
         if ($menuGroup[$i]['menu_parent_id']==0) {
            $parent=$menuGroup[$i]['menu_name'];
            $this->mrTemplate->addVar('menu', 'MENU_PARENT', 'YES');
            $this->mrTemplate->addVar('menu', 'PARENT_MENU', $parent);
            $this->mrTemplate->parseTemplate('menu', 'a'); 
            for ($j=$i;$j<$len;$j++) { 
               if ($menuGroup[$j]['menu_parent_id']==$menuGroup[$i]['menu_id']) {
                  $idmenu=$menuGroup[$j]['menu_id'];
                  $menu_name=$menuGroup[$j]['menu_name'];
                  $htmlField = '';
                  foreach($data['actionGroup'][$idmenu] as $val){
                  	if ($val['AksiLabel'] <> ''){
	                  	$label='';
	                  	if($val['LabelAksi'] <> '') $label = '('.ucwords(str_replace('_',' ',$val['LabelAksi'])).')';
	                  	if ($val['Checked'] == 1)
	                  		$htmlField .= '<li><input type="checkbox" class="checkact childCheckbox" name="aksi[]" value="'.$val['ModuleId'].'" checked>'.$val['AksiLabel'].' '.$label.'</li>';
	                  	else
	                  		$htmlField .= '<li><input type="checkbox" class="checkact childCheckbox" name="aksi[]" value="'.$val['ModuleId'].'" >'.$val['AksiLabel'].' '.$label.'</li>';
                  	}
                  }             
                  for ($k=0;$k<$mlen;$k++) { 
                     #if ($menuGroup[$j]['menu_name']==$menu[$k]) {
                     if (!empty($menuGroup[$j]['MenuName'])) {
                         $this->mrTemplate->addVar('menu', 'CHECK', 'checked'); 
                         break;
                     } else $this->mrTemplate->addVar('menu', 'CHECK', '');
                  } 
                  $this->mrTemplate->addVar('menu', 'MENU_PARENT', 'NO');
                  $this->mrTemplate->addVar('menu', 'ACTIONLIST',$htmlField);
                  $this->mrTemplate->addVar('menu', 'IDMENU', $idmenu);  
                  $this->mrTemplate->addVar('menu', 'MENU', $menu_name);       
                  $this->mrTemplate->parseTemplate('menu', 'a');
               } 
            } 
        }
     }
     
   }
}
?>
