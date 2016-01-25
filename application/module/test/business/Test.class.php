<?php
/**
 * @author Prima Noor /** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

 
class Test extends Database
{

    function __construct($connectionNumber = 0)
    {
        parent::__construct($connectionNumber);
        $this->LoadSql('module/test/business/test.sql.php');
        $this->SetDebugOn();
    }

    public function getPengguna()
    {
    	return $this->Open($this->mSqlQueries['get_pengguna'], array());
    }
}

?>