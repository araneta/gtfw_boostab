<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'main/function/warning.php';

class Request {
   var $POST;
   
   function Request() {
      $this->POST = $_POST->AsArray();
   }

   public static function DecodeSanitization($value) {
      $pattern[0] = '/&amp/';
      $pattern[1] = '/&lt/';
      $pattern[2] = '/&gt/';
      $pattern[3] = '/\<br\>/';
      $pattern[4] = '/\&quot/';
      $pattern[5] = '/&#39/';
      $pattern[6] = '/&#37/';
      $pattern[7] = '/&#40/';
      $pattern[8] = '/&#41/';
      $pattern[9] = '/&#43/';
      $pattern[10] = '/&#45/';
      $replacement[0] = '&';
      $replacement[1] = '<';
      $replacement[2] = '>';
      $replacement[3] = "\n";
      $replacement[4] = '"';
      $replacement[5] = "'";
      $replacement[6] = '%';
      $replacement[7] = '(';
      $replacement[8] = ')';
      $replacement[9] = '+';
      $replacement[10] = '-';
      return preg_replace($pattern, $replacement, $value);
   }
   
   function IsEmpty($formName, $label = null, $modul = null, $sub_modul = null, $css = 'notebox-alert') {
      $POST = $_POST->AsArray();
      if( ! isset($POST[$formName])) {
         if (!is_null($modul) && !is_null($sub_modul) && !is_null($label))
            Warning::SendAlert('empty_input', array($label), $modul, $sub_modul, $css);
         return true;
      } else {
         return false;
      }
   }
   
   function IsEmptyText($formName, $label = null, $modul = null, $sub_modul = null, $css = 'notebox-alert') {
      $POST = $_POST->AsArray();
      if(isset($POST[$formName]) && trim($POST[$formName]) == '') {
         if (!is_null($modul) && !is_null($sub_modul) && !is_null($label))
            Warning::SendAlert('empty_input', array($label), $modul, $sub_modul, $css);
         return true;
      } else {
         return false;
      }
   }
   
   // equal to IsEmptyText();
   function IsEmptySelect($formName, $label = null, $modul = null, $sub_modul = null) {
      return IsEmptyText($formName, $label, $modul, $sub_modul);
   }
   
   function IsEmptySelectByValue($value, $label = null, $modul = null, $sub_modul = null, $css = 'notebox-alert') {
      if(isset($value) && trim($value) == '') {
         if (!is_null($modul) && !is_null($sub_modul) && !is_null($label))
            Warning::SendAlert('empty_input', array($label), $modul, $sub_modul, $css);
         return true;
      } else {
         return false;
      }
   }
   
   // IsEmptyFile() not yet implemented
   /*function IsEmptyFile($formName, $label, $sub_modul) {
      if(        ) {
         Warning::SendAlert('empty_input', array($formName), $modul, $sub_modul);
         return true;
      } else {
         return false;
      }
   }*/
 }
?>