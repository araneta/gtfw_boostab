<?php
/**
 * @copyright Copyright (c) 2014, PT Gamatechno Indonesia
 * @license http://gtfw.gamatechno.com/#license
 */

class ViewTesSatu extends HtmlResponse
{

    public function TemplateModule()
    {
        $this->setTemplateBasedir(
            Configuration::Instance()->GetValue('application', 'docroot') . 'module/tes_satu/template'
        );
        $this->setTemplateFile('view_tes_satu.html');
    }

    public function ProcessRequest()
    {
        $return['NAMA'] = 'Gamatechno';
        $return['EMAIL'] = 'test@gamatechno.com';

        return $return;
    }

    public function ParseTemplate($data = null)
    {
        $this->mrTemplate->addVar('info', 'NAMA', $data['NAMA']);
        $this->mrTemplate->addVar('info', 'EMAIL', $data['EMAIL']);
    }

}
