<?php
/**
* @author Prima Noor /** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/


class ViewDetail extends HtmlResponse
{
    function TemplateModule()
    {
        $this->SetTemplateBasedir(Configuration::Instance()->GetValue('application','docroot').'module/'.Dispatcher::Instance()->mModule.'/template');
        $this->SetTemplateFile('view_detail.html');
    }

    function ProcessRequest()
    {    
        return compact('');
    }

    function ParseTemplate($data = NULL)
    {
        if (is_array($data))
            extract($data);

    }
}
?>