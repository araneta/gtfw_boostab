<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class ViewCombobox extends HtmlResponse {

   var $mComponentParameters;

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/combobox/template');
      $this->SetTemplateFile('view_combobox.html');
   }

   function ProcessRequest() {
      
      $msg = Messenger::Instance()->Receive(__FILE__,$this->mComponentName);
      $return['idNama']    = !empty($msg[0][0])?$msg[0][0]:'';
      $return['arrData']   = !empty($msg[0][1])?$msg[0][1]:'';
         #lebih baik "id" dan "name" di typecast ke string dulu. 
      $return['id']        = !empty($msg[0][2])?$msg[0][2]:'';
      $return['all']       = !empty($msg[0][3])?$msg[0][3]:'';
      $return['action']    = !empty($msg[0][4])?$msg[0][4]:'';
      $return['multiple']  = !empty($msg[0][5])?$msg[0][5]:'';
      $return['size']      = !empty($msg[0][6])?$msg[0][6]:'';
      $return['inputId']   = !empty($msg[0][7])?$msg[0][7]:'';
      return $return;
   }

   function ParseTemplate($data = NULL) {
      
      if(!empty($data)) {
         $all = $data["all"];
         $mTemplate = "combolist";
         $mTemplateID = "COMBO";
         $mArray = $data["arrData"];
         $mId = $data["id"];
         $mMultiple = $data["multiple"]?'multiple="multiple"':'';
         $mSize = $data["size"];
         $mInputId = $data["inputId"];
         
         $this->mrTemplate->addVar("combobox", "COMBO_NAME", $data["idNama"]);
         $this->mrTemplate->addVar("combobox", "COMBO_ID", $data["inputId"]);
         $this->mrTemplate->addVar("combobox", "ACTION", $data["action"]);
         if ($data["multiple"]) {
            $this->mrTemplate->addVar("combobox", "MULTIPLE", $mMultiple);
            $this->mrTemplate->addVar("combobox", "SIZE", "size=\"$mSize\"");
         }
   
         if ( ! $data["multiple"]) {
            if ($all == "true") {
                  $this->mrTemplate->addVar("$mTemplate", "$mTemplateID", "-- SEMUA --");
                  $this->mrTemplate->addVar("$mTemplate", $mTemplateID."_VALUE", "all");
                  $this->mrTemplate->parseTemplate("$mTemplate","a");
            } else if ($all == "false") {
                     $this->mrTemplate->addVar("$mTemplate", "$mTemplateID", "-- PILIH --");
                     $this->mrTemplate->parseTemplate("$mTemplate","a");
            }
         }
            
            
            //print_r($mArray);exit;
         if(!empty($mArray))
            for ($i=0;$i<sizeof($mArray);$i++) {
               if (empty($mArray[$i]['id'])) {
                  $mArray[$i]['id'] = $mArray[$i]['ID'];
               }
               if (empty($mArray[$i]['name'])) {
                  $mArray[$i]['name'] = $mArray[$i]['NAME'];
               }

               if ((($mArray[$i]['id'] == trim($mId)) && ($mId != ""))
                  || (is_array($mId) && in_array($mArray[$i]['id'], $mId, true))) {  
                  $this->mrTemplate->addVar("$mTemplate", $mTemplateID."_SELECTED", "SELECTED");
               }
               else {
                  $this->mrTemplate->addVar("$mTemplate", $mTemplateID."_SELECTED", "");
               }
               
               $this->mrTemplate->addVar("$mTemplate", $mTemplateID."_VALUE", $mArray[$i]['id']);
               $this->mrTemplate->addVar("$mTemplate", "$mTemplateID", $mArray[$i]['name']);
      
               $this->mrTemplate->parseTemplate("$mTemplate","a");
            }
         }
      }
   }
?>