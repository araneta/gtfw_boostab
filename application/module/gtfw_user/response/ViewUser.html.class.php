<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/gtfw_user/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppUser.class.php';

class ViewUser extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').'module/gtfw_user/template');
      $this->SetTemplateFile('view_user.html');
   }
   
   function ProcessRequest() {
   
      $msg = Messenger::Instance()->Receive(__FILE__);
      $userObj = new AppUser();
      $filter_data = !empty($msg[0][0])? $msg[0][0]:NULL;
      
      $message['content'] = !empty($msg[1][1])?$msg[1][1]:NULL;
      $message['style'] = !empty($msg[1][2])?$msg[1][2]:NULL;
      
      $view_per_page = Configuration::Instance()->GetValue('application', 'paging_limit');
      $view_per_page = 5;
      if (!isset($_GET['display']) || empty($filter_data)) {
          $page = 1;
          $start = 0;
          $display = $view_per_page;
          $filter = compact('page', 'display', 'start');
      } elseif ($_GET['display']->Raw() != '') {
          $page = (int)$_GET['page']->SqlString()->Raw();
          $display = (int)$_GET['display']->SqlString()->Raw();
      
          if ($page < 1)
              $page = 1;
          if ($display < 1)
              $display = $view_per_page;
          $start = ($page - 1) * $display;
      
          $filter = compact('page', 'display', 'start');
          $filter += $filter_data;
      } else {
          $filter = $filter_data;
          $page = $filter['page'];
          $display = $filter['display'];
          $start = $filter['start'];
      }
      
      $post_data = $_POST->AsArray();
      if (!empty($post_data)) {
          foreach ($post_data as $key => $value)
              $filter[$key] = $value;
      }
      Messenger::Instance()->Send(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType, array($filter), Messenger::UntilFetched);
              
        $data   = $userObj->getData($filter);
        $total  = $userObj->countData();
      
      $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType).'&display='.$view_per_page;
      Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($display, $total, $url, $page), Messenger::CurrentRequest);
      
        return compact('data', 'filter', 'message');
    }
      
   function ParseTemplate($data = NULL) {
      $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('gtfw_user', 'inputUser', 'view', 'html') );
      if (is_array($data))
       extract($data);

      if (!empty($message)) {
       $this->mrTemplate->SetAttribute('message', 'visibility', 'visible');
       $this->mrTemplate->addVars('message', $message);
      }

      $this->mrTemplate->addVar('search', 'URL', Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType));
      
      if (!empty($filter)) {
      $this->mrTemplate->addVars('search', $filter);
      }

      if (!empty($data)) {
        $this->mrTemplate->addVar('data', 'IS_EMPTY', 'NO');
        $dataUser = $data;
        $len = sizeof($dataUser);
        $no = $filter['start'] + 1;
        foreach ($data as $val) {

          if ($val['active'] == 'Yes') {
            $val['status'] = 'aktif';
          } else {
            $val['status'] = 'tidak aktif';
          }

        $val['number'] = $no;
        $val['class_name'] = $no%2 == 0?'even':'odd';  
        
        //DELETE  
        $label = 'User';
        $idEnc = Dispatcher::Instance()->Encrypt($val['user_id']);
        $dataName = Dispatcher::Instance()->Encrypt($val['user_name']);
        $urlAccept = 'gtfw_user|deleteUser|do|json';
        $urlReturn = 'gtfw_user|user|view|html';
        $val['URL_DELETE']=Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='.$urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
        $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
        $val['url_edit'] = Dispatcher::Instance()->GetUrl('gtfw_user', 'inputUser', 'view', 'html') . 
        '&usr=' . $idEnc;
        $val['url_updatepassword'] = Dispatcher::Instance()->GetUrl('gtfw_user', 'changePassword', 'view', 'html') . 
        '&usr=' . $idEnc;

        if($_SESSION['username']==$val['user_name']){
          $val['display_status'] = 'none';
        }else{
          $val['display_status'] = '';
        }
        $this->mrTemplate->addVars('data_user_item', $val);
        $this->mrTemplate->parseTemplate('data_user_item', 'a');
        $no++;
      }
    } else {
      $this->mrTemplate->addVar('data', 'IS_EMPTY', 'YES'); 
    }
  } 
}
?>