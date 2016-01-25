<?php
/**
* @author Prima Noor /** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

	require_once 'module/'.Dispatcher::Instance()->mModule.'/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Menu.class.php';

class ViewInput extends HtmlResponse
{
    function TemplateModule()
    {
        $this->SetTemplateBasedir(Configuration::Instance()->GetValue('application','docroot').'module/'.Dispatcher::Instance()->mModule.'/template');
        $this->SetTemplateFile('view_input.html');
    }

    function ProcessRequest()
    {    
    	$Obj = new Menu;
        $id     = $_GET['id']->Integer()->Raw();

        $msg    = Messenger::Instance()->Receive(__FILE__);
        $post  = !empty($msg[0][0])? $msg[0][0]:NULL;
    	
    	$message['content'] = !empty($msg[0][1])?$msg[0][1]:NULL;
    	$message['style'] = !empty($msg[0][2])?$msg[0][2]:NULL;

    	$input = array();
    	if (!empty($post)) {
    		$input = $post;
    	} elseif (!empty($id)) {
    		$input = $Obj->getDetail($id);
    	}

    	$list_menu = $Obj->listMenu();
    	Messenger::Instance()->SendToComponent('combobox', 'combobox', 'view', 'html', 'parent_id', array('parent_id',$list_menu,isset($input['parent_id'])?$input['parent_id']:'','false',' class="form-control"'), Messenger::CurrentRequest);
    	$list_yesno = array(
    		array(
    			'id' => 'Yes',
    			'name' => 'Yes',
    			),
    		array(
    			'id' => 'No',
    			'name' => 'No',
    			),
    		);
    	Messenger::Instance()->SendToComponent('combobox', 'combobox', 'view', 'html', 'is_show', array('is_show',$list_yesno,isset($input['is_show'])?$input['is_show']:'','',' class="form-control"'), Messenger::CurrentRequest);

        return compact('id', 'input', 'message');
    }

    function ParseTemplate($data = NULL)
    {
        if (is_array($data))
            extract($data);

        $this->mrTemplate->addVar('content', 'ACTION', empty($id)?'Tambah':'Ubah');

        if (!empty($message)) {
            $this->mrTemplate->addVars('message', $message);
        }
        if (!empty($input)) {
            $this->mrTemplate->addVars('content', $input);
        }

        $this->mrTemplate->addVar('content', 'URL', Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, empty($id)?'add':'update', 'do', 'html').'&id='.$id);
        $this->mrTemplate->addVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'menu', 'view', 'html'));
    }
}
?>