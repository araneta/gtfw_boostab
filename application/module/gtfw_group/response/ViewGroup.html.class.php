<?php
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/gtfw_group/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppGroup.class.php';

class ViewGroup extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').
         'module/gtfw_group/template');
      $this->SetTemplateFile('view_group.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      if($msg){
	      $return['Pesan'] = $msg[0][1];
	      $return['css'] = $msg[0][2];
      }else{
      	$return['Pesan'] = null;
	      $return['css'] = null;
      }
		
		$applicationId = Configuration::Instance()->GetValue( 'application', 'application_id');
      
      $groupObj = new AppGroup();
      $dataGroup = $groupObj->GetDataGroup('', $applicationId, true);
      $return['dataGroup'] = $dataGroup;
		
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('gtfw_group', 'group', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'GROUP_URL_ADD', Dispatcher::Instance()->GetUrl('gtfw_group', 'inputGroup', 'view', 'html') );

      if ($data['Pesan']){
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }
      
      if (empty($data['dataGroup'])) {
         $this->mrTemplate->AddVar('data_group', 'GROUP_EMPTY', 'YES');
      } else {
         $this->mrTemplate->AddVar('data_group', 'GROUP_EMPTY', 'NO');
         $dataGroup = $data['dataGroup'];
         $len = sizeof($dataGroup);
         $menuName='';
         $idGroup='';
         $no=0;
         for ($i=0; $i<$len; $i++) {
            if($idGroup!=$dataGroup[$i]['group_id']){
               $no++;
               $menuBaru[$no]['no']=$no;
               $menuBaru[$no]['group_id']=$dataGroup[$i]['group_id'];
               $menuBaru[$no]['group_name']=$dataGroup[$i]['group_name'];
               $menuBaru[$no]['group_description']=$dataGroup[$i]['group_description'];
               $menuBaru[$no]['unit_kerja']=$dataGroup[$i]['unit_kerja'];
               $menuBaru[$no]['hak_akses'] = '';
               $idGroup=$dataGroup[$i]['group_id'];
               $menuName='';
            }
            if($dataGroup[$i]['menu_name']!=$menuName){
               $menuBaru[$no]['hak_akses'] .='<strong>'.$dataGroup[$i]['menu_name'].'</strong><br>'.'&nbsp;&nbsp;&#45;&nbsp;'.$dataGroup[$i]['sub_menu'].'<br>';
               $menuName=$dataGroup[$i]['menu_name'];
            } else {
               $menuBaru[$no]['hak_akses'].='&nbsp;&nbsp;&#45;&nbsp;'.$dataGroup[$i]['sub_menu'].'<br>';
            }
         }
         
         
         $no=1;
         for($i=1;$i<count($menuBaru)+1;$i++){            
            $menuBaru[$i]['number'] = $no;
            if ($no % 2 != 0) {
               $dataGroup[$i]['class_name'] = 'table-common-even';
            } else {
               $dataGroup[$i]['class_name'] = '';
            }
            $no++;
            $idEnc = Dispatcher::Instance()->Encrypt($menuBaru[$i]['group_id']);
            $menuBaru[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('gtfw_group', 'inputGroup', 'view', 'html') . '&grp=' . $idEnc;

            $idEnc = Dispatcher::Instance()->Encrypt($menuBaru[$i]['group_id']);
                        
            $urlAccept = 'gtfw_group|deleteGroup|do|html-cari-';
            $urlReturn = 'gtfw_group|group|view|html-cari-';
            $label = 'Group';
            $dataName = $menuBaru[$i]['group_name'];
            $menuBaru[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;

                  $this->mrTemplate->AddVars('data_group_item', $menuBaru[$i], 'GROUP_');
                  $this->mrTemplate->parseTemplate('data_group_item', 'a');
         }
      }
   }
}
?>
