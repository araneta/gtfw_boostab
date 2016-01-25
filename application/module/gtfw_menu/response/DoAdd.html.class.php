<?php
/**
* @author Prima Noor /** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class DoAdd extends HtmlResponse
{

    function ProcessRequest()
    {    
    	require_once GTFW_APP_DIR.'module/gtfw_menu/response/Process.proc.class.php';
    	$Proc = new Process;

    	$post = $_POST->AsArray();
    	$result = $Proc->input();
    	if ($result == true) {
    		Messenger::Instance()->Send('gtfw_menu', 'menu', 'view', 'html', array(NULL, 'Penambahan data berhasil', 'notebox-done'), Messenger::NextRequest);
    		$redirect = Dispatcher::Instance()->GetUrl('gtfw_menu', 'menu', 'view', 'html').'&display';
    	} else {
    		Messenger::Instance()->Send('gtfw_menu', 'input', 'view', 'html', array($post, implode('<br/>', $Proc->err_msg), 'notebox-warning'), Messenger::NextRequest);
    		$redirect = Dispatcher::Instance()->GetUrl('gtfw_menu', 'add', 'view', 'html');
    	}

        $this->RedirectTo($redirect);
    }
}
?>