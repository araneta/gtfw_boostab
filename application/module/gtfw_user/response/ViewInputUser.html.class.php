<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/gtfw_user/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppUser.class.php';

class ViewInputUser extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/gtfw_user/template');
      $this->SetTemplateFile('input_user.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      if($msg){
	      $return['Pesan'] = $msg[0][1];
	      $return['Data'] = $msg[0];
      }else{
      	$return['Pesan'] = null;
      $return['Data'] = null;
      }
      $decUsr = Dispatcher::Instance()->Decrypt($_REQUEST['usr']);
      if ($decUsr == '')
         $decUsr = Dispatcher::Instance()->Decrypt($return['Data']['0']['usr']);
      
      $applicationId = Configuration::Instance()->GetValue('application', 'application_id');

      $userObj = new AppUser();

      $dataUser = $userObj->GetDataUserById($decUsr);
		
      $dataUnitKerja = $userObj->GetComboUnitKerja($applicationId);

      if (isset($dataUser['0']['unit_kerja_id']))
         $unit_selected = $dataUser['0']['unit_kerja_id'];
      else
         $unit_selected = $return['Data']['0']['unit_kerja'];

      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit_kerja', 
         array('unit_kerja',$dataUnitKerja,$unit_selected,'false','onChange="updateGroup();"',false,'','','',''), Messenger::CurrentRequest);

      if (isset($unit_selected)) {
         $data_combo_group = $userObj->GetDataGroupByUnitId("", $unit_selected, $applicationId);
         if ($_REQUEST['usr']=='')
            $group_selected = $return['Data']['0']['group'];
         else
            $group_selected = $dataUser['0']['group_id'];
      } else {
      	$group_selected = null;
         $data_combo_group = null;
      }
      
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'group', 
         array('group',$data_combo_group,$group_selected,'false','','','',''), Messenger::CurrentRequest);
      
      $return['dataUser'] = $dataUser;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $dataUser = $data['dataUser'];
      $dataUserUbah = $data['Data']; 
      if ($data['Pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         
         $status='checked="checked"';
         $nstatus='';
         if (isset($dataUserUbah[0]['status']) && $dataUserUbah[0]['status']!='Yes') {
            $status='';
            $nstatus='checked="checked"';
         }
         $this->mrTemplate->AddVar('content', 'USERNAME', $dataUserUbah[0]['username']);
         $this->mrTemplate->AddVar('content', 'USR', $dataUserUbah[0]['usr']);
         $this->mrTemplate->AddVar('content', 'CARI', $_GET['cari']);
         $this->mrTemplate->AddVar('content', 'REALNAME', $dataUserUbah[0]['realname']);
         $this->mrTemplate->AddVar('content', 'DESKRIPSI', $dataUserUbah[0]['deskripsi']);
         $this->mrTemplate->AddVar('content', 'USER_ID', $dataUserUbah[0]['user_id']);
      } else {
         $status='checked="checked"';
         $nstatus='';
         if (isset($dataUser[0]['is_active']) && $dataUser[0]['is_active']!='Yes') {
            $status='';
            $nstatus='checked="checked"';
         }
   		if($dataUser){
	         $this->mrTemplate->AddVar('content', 'USERNAME', $dataUser[0]['user_name']);
	         $this->mrTemplate->AddVar('content', 'USR', Dispatcher::Instance()->Encrypt($dataUser[0]['user_id']));
	         $this->mrTemplate->AddVar('content', 'CARI', $_GET['cari']);
	         $this->mrTemplate->AddVar('content', 'REALNAME', $dataUser[0]['real_name']);
	         $this->mrTemplate->AddVar('content', 'DESKRIPSI', $dataUser[0]['description']);
	         $this->mrTemplate->AddVar('content', 'USER_ID', $dataUser[0]['user_id']);
   		}
      }

      if (empty($dataUser)) {
         $this->mrTemplate->SetAttribute('view_password', 'visibility', 'visible');
         $url="addUser";
         $tambah="Tambah";
      } else {
         $url="updateUser";
         $tambah="Ubah";  
      }
      $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
      
      $this->mrTemplate->AddVar('content', 'STATUS', $status);
      $this->mrTemplate->AddVar('content', 'NSTATUS', $nstatus);
      
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('gtfw_user', $url, 'do', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_VIEW', Dispatcher::Instance()->GetUrl('gtfw_user', 'user', 'view', 'html') );
   }
}
?>
