<?php
/** 
* @author Rabiul Akhirin <roby@gamatechno.com>
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class Header extends Database
{
   protected $mSqlFile;

   function __construct ($connectionNumber=0)
   {
      $this->mSqlFile = 'module/header/business/'.Configuration::Instance()->GetValue('application',array('db_conn',0,'db_type')).'/header.sql.php';
      if($connectionNumber == 0){
         $connectionNumber = Configuration::Instance()->GetValue('application', 'gtakademik_conn');
      }
      parent::__construct($connectionNumber);
      #$this->SetDebugOn();
   }

   public function GetPeriodeAktif() {
      $result = $this->Open($this->mSqlQueries['GetPeriodeAktif'], array());
      return $result;
   }

}
?>
