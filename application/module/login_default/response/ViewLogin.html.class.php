<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/ 

class ViewLogin extends HtmlResponse {

    function TemplateModule() {
        $this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot') .
            'module/login_default/template');
        $this->SetTemplateFile('view_login.html');
    }

    function ProcessRequest() {

        if (Security::Instance()->IsLoggedIn()) {
// redirect to proper place
            $module = 'home';
            $submodule = 'home';
            $action = 'view';
            $type = 'html';
            $this->RedirectTo(Dispatcher::Instance()->GetUrl($module, $submodule, $action, $type));
            return NULL;
        }
        echo '<pre>'; print_r(Security::Instance()->IsLoggedIn()); echo '</pre>';
        return Security::Instance()->RequestSalt();
    }

    function TemplateBase() {
        $this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot') . 'main/template/');
        $this->SetTemplateFile('document-login.html');

        $this->SetTemplateFile('layout-common-login.html');
    }

    function ParseTemplate($data = NULL) {
//syslog::log('ggfhg','jhh');

        if (isset($_GET['fail'])) {
            $this->mrTemplate->addVar('error', 'IS_ERROR', 'TRUE');
        }

        $this->mrTemplate->AddVar('document', 'LOADER_NAME_ADDITONAL', '-login');
        $_SESSION['login_salt'] = $data;
        if (!isset($_GET['fail'])) {
            $this->SetBodyAttribute('onload', '"document.form_login.username.focus();"');
        }
        $this->mrTemplate->AddVar('head_addition', 'APP_BASE_ADDRESS', Configuration::Instance()->GetValue('application', 'baseaddress') . 
            Configuration::Instance()->GetValue('application', 'basedir'));
        $this->mrTemplate->AddVar('content', 'LOGIN_SALT', $data);
    }
}
?>
