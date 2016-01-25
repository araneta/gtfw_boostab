<?php
/**
 * @author Prima Noor/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once GTFW_APP_DIR.'module/gtfw_menu/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Menu.class.php';
 
class Process
{
    var $Obj;
    var $user;
    var $cssDone = 'success';
    var $cssFail = 'danger';
    var $cssAlert = 'warning';
   	public $err_msg = array();

    function __construct()
    {
        $this->Obj      = new Menu;
        $this->user     = Security::Authentication()->GetCurrentUser()->GetUserId();
    }

    private function check_input($post)
    {
    	$result = true;
    	if (empty($post['name'])) {
    		$this->err_msg[] = "Nama menu harus diisi";
    		$result &= false;
    	}
    	return $result;
    }

    function input()
    {
        $post = $_POST->AsArray();
        
        $app_id = Configuration::Instance()->GetValue('application', 'application_id');
        
        $result = $this->check_input($post);
        $msg = '';
        if ($result) {
            if (empty($post['data-id'])) {
                $this->Obj->StartTrans();
                $params = array(
                    $post['parent_id']     // MenuParentId,
                    ,$post['name']          // MenuName,
                    ,$post['is_show']       // IsShow,
                    ,$post['icon']          // IconPath,
                    ,$post['order']         // MenuOrder,
                    ,$app_id                // ApplicationId
                );
                if ($result) $result = $this->Obj->add($params);
                if ($result) {
                    $msg .= 'Penambahan data Berhasil Dilakukan';
                    $css = $this->cssDone;
                } else {
                    $msg .= 'Gagal Menambah Data';
                    $css = $this->cssFail;
                }
                $this->Obj->EndTrans($result);
                
            } else {
                $this->Obj->StartTrans();
                $params = array(
                    $post['parent_id']     // MenuParentId
                    ,$post['name']          // MenuName
                    ,$post['is_show']       // IsShow
                    ,$post['icon']          // IconPath
                    ,$post['order']         // MenuOrder
                    ,$app_id                // ApplicationId
                    ,$post['data-id']
                );
                if ($result) $result = $this->Obj->edit($params);
                if ($result) {
                    $msg .= 'Pengubahan data Berhasil Dilakukan';
                    $css = $this->cssDone;
                } else {
                    $msg .= 'Gagal Mengubah Data';
                    $css = $this->cssFail;
                } 
                $this->Obj->EndTrans($result); 
                
            }
        } 
        if ($result) {
            Messenger::Instance()->Send('gtfw_menu', 'menu', 'view', 'html', array(NULL, $msg, $css), Messenger::NextRequest);
        } else {
            Messenger::Instance()->Send('gtfw_menu', 'menu', 'view', 'html', array($post, $msg, $css), Messenger::NextRequest);
        }  
        return $result;
    }
}

?>