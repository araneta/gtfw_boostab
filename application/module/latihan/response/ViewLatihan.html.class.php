<?php
/**
* @author Prima Noor /** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/


class ViewLatihan extends HtmlResponse
{
    function TemplateModule()
    {
        $this->SetTemplateBasedir(Configuration::Instance()->GetValue('application','docroot').'module/'.Dispatcher::Instance()->mModule.'/template');
        $this->SetTemplateFile('view_latihan.html');
    }

    function ProcessRequest()
    {    
        

        // return compact('links');
    }

    function ParseTemplate($data = NULL)
    {
        // if (is_array($data))
        //     extract($data);

        // if (!empty($links)) {
        //     $this->mrTemplate->addVar('data', 'IS_EMPTY', 'NO');
        //     foreach ($links as $val) {
        //         if ($val['ajax']) {
        //             $val['class'] = 'xhr dest_subcontent-element';
        //         } else {
        //             $val['target'] = '_blank';
        //         }
        //         $this->mrTemplate->addVars('item', $val);
        //         $this->mrTemplate->parseTemplate('item', 'a');
        //     }
        // } else {
        //     $this->mrTemplate->addVar('data', 'IS_EMPTY', 'YES');
        // }

        // $this->mrTemplate->addVar('content', 'TANGGAL', date('Y-m-d'));
    }
}
?>