<?php
/**
* @author Prima Noor /** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once GTFW_APP_DIR.'module/gtfw_menu/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Menu.class.php';

class ViewMenu extends HtmlResponse
{
    function TemplateModule()
    {
        $this->SetTemplateBasedir(Configuration::Instance()->GetValue('application','docroot').'module/'.Dispatcher::Instance()->mModule.'/template');
        $this->SetTemplateFile('view_menu.html');
    }

    function ProcessRequest()
    {    
    	$Obj = new Menu;
    	$msg = Messenger::Instance()->Receive(__FILE__);
    	$filter_data = !empty($msg[0][0])? $msg[0][0]:NULL;
    	$message['content'] = !empty($msg[0][1])?$msg[0][1]:NULL;
    	$message['style'] = !empty($msg[0][2])?$msg[0][2]:NULL;

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
            $url_add = "&$key=$value";

            $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType).$url_add.'&display='.$view_per_page;

    	} else {
           $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType, false, true).'&display='.$view_per_page;
        }
        Messenger::Instance()->Send(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType, array($filter), Messenger::UntilFetched);
                
        $data   = $Obj->getData($filter);
        $total  = $Obj->countData();
                
    	Messenger::Instance()->SendToComponent('paging', 'paging', 'view', 'html', 'paging_top', array($display, $total, $url, $page), Messenger::CurrentRequest);
    	
        return compact('data', 'filter', 'message');
    }

    function ParseTemplate($data = NULL)
    {
        if (is_array($data))
            extract($data);

        if (!empty($message)) {
            $this->mrTemplate->addVars('message', $message);
        }
        $this->mrTemplate->addVar('search', 'URL', Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType));
        if (!empty($filter)) {
            $this->mrTemplate->addVars('search', $filter);
        }

        if (!empty($data)) {
            $this->mrTemplate->addVar('data', 'IS_EMPTY', 'NO');
            $no = $filter['start'] + 1;
            foreach ($data as $val) {
                $val['no'] = $no;
                $val['row_class'] = $no%2 == 0?'even':'odd';
                $val['url_edit'] = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'update', 'view', 'html').'&id='.$val['id'];
                $this->mrTemplate->addVars('item', $val);
                $this->mrTemplate->parseTemplate('item', 'a');
                $no++;
            }
        } else {
            $this->mrTemplate->addVar('data', 'IS_EMPTY', 'YES');
        }

        $this->mrTemplate->addVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'add', 'view', 'html'));

    }
}
?>