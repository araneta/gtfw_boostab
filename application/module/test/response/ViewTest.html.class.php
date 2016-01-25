<?php
/**
* @author Prima Noor /** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/


class ViewTest extends HtmlResponse
{
    function TemplateModule()
    {
        $this->SetTemplateBasedir(Configuration::Instance()->GetValue('application','docroot').'module/'.Dispatcher::Instance()->mModule.'/template');
        $this->SetTemplateFile('view_test.html');
    }

    function ProcessRequest()
    {    
        $links = array(
            array(
                'label' => 'XlsResponse',
                'ajax'  => false,
                'url'   => Dispatcher::Instance()->GetUrl('test', 'excel', 'view', 'xls')
            ),
            array(
                'label' => 'XlsxResponse',
                'ajax'  => false,
                'url'   => Dispatcher::Instance()->GetUrl('test', 'excelNew', 'view', 'xlsx')
            ),
            array(
                'label' => 'PdfxResponse Sample 1',
                'ajax'  => false,
                'url'   => Dispatcher::Instance()->GetUrl('test', 'pdfx', 'view', 'pdfx')
            ),
            array(
                'label' => 'PdfxResponse Sample 2',
                'ajax'  => false,
                'url'   => Dispatcher::Instance()->GetUrl('test', 'pdfx2', 'view', 'pdfx')
            ),
            array(
                'label' => 'PdfxResponse Sample 3',
                'ajax'  => false,
                'url'   => Dispatcher::Instance()->GetUrl('test', 'pdfx3', 'view', 'pdfx')
            ),
            array(
                'label' => 'PdfxResponse Sample 4',
                'ajax'  => false,
                'url'   => Dispatcher::Instance()->GetUrl('test', 'pdfx4', 'view', 'pdfx')
            ),
            array(
                'label' => 'PdfxResponse Sample 5',
                'ajax'  => false,
                'url'   => Dispatcher::Instance()->GetUrl('test', 'pdfx5', 'view', 'pdfx')
            ),
            array(
                'label' => 'PdfxResponse Sample 33',
                'ajax'  => false,
                'url'   => Dispatcher::Instance()->GetUrl('test', 'pdfx33', 'view', 'pdfx')
            ),
            array(
                'label' => 'PdfxResponse Sample 65',
                'ajax'  => false,
                'url'   => Dispatcher::Instance()->GetUrl('test', 'pdfx65', 'view', 'pdfx')
            ),
            // array(
            //     'label' => 'TinyMCE',
            //     'ajax'  => true,
            //     'url'   => Dispatcher::Instance()->GetUrl('test', 'tinymce', 'view', 'html')
            // ),
        );
        // $key = Dispatcher::Instance()->mStartKey;
        // $ori = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~!@#$%^&*()-_=+`,<.>/?'\";:[{]}\|";
        // $enc = Dispatcher::Instance()->Encrypt($ori);
        // $dec = Dispatcher::Instance()->Decrypt($enc);

        // echo '<pre>'; print_r(compact('key', 'ori', 'enc', 'dec')); echo '</pre>';

        return compact('links');
    }

    function ParseTemplate($data = NULL)
    {
        if (is_array($data))
            extract($data);

        if (!empty($links)) {
            $this->mrTemplate->addVar('data', 'IS_EMPTY', 'NO');
            foreach ($links as $val) {
                if ($val['ajax']) {
                    $val['class'] = 'xhr dest_subcontent-element';
                } else {
                    $val['target'] = '_blank';
                }
                $this->mrTemplate->addVars('item', $val);
                $this->mrTemplate->parseTemplate('item', 'a');
            }
        } else {
            $this->mrTemplate->addVar('data', 'IS_EMPTY', 'YES');
        }

        $this->mrTemplate->addVar('content', 'TANGGAL', date('Y-m-d'));
    }
}
?>