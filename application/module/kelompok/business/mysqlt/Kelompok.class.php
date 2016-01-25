<?php

class Kelompok extends Database
{
   # internal variables
   protected $mSqlFile;
   public $_POST;
   public $_GET;
   # Constructor
   function __construct ($connectionNumber = 0)
   {
      $db_drive         = Configuration::Instance()->GetValue('application','db_conn',0,'db_type');
      $this->mSqlFile   = 'module/'.Dispatcher::Instance()->mModule.'/business/'.$db_drive.'/kelompok.sql.php';
      $this->_POST      = is_object($_POST) ? $_POST->AsArray() : $_POST;
      $this->_GET       = is_object($_GET) ? $_GET->AsArray() : $_GET;
      parent::__construct($connectionNumber);
   }

   public function Count()
   {
      $return     = $this->Open($this->mSqlQueries['count'], array());

      if($return){
         return (int)$return[0]['count'];
      }else{
         return 0;
      }
   }

   public function getDataKelompok($offset, $limit, $param = array())
   {
      $return  = $this->Open($this->mSqlQueries['get_data_kelompok'], array(
         '%'.$param['nama'].'%',
         $offset,
         $limit
      ));

      return $return;
   }
   public function doCheckData($nama, $id = NULL)
	{
	   $return     = $this->Open($this->mSqlQueries['do_check_data'], array(
	      $id,
	      (int)($id === NULL OR $id == ''),
	      $nama
	   ));

	   if($return AND (int)$return[0]['count'] <> 0){
	      return false;
	   } else {
	      return true;
	   }
	}

	public function doInsertKelompok($param = array())
	{
	   $result     = true;
	   $this->StartTrans();
	   if(!is_array($param)){
	      $result  &= false;
	   }

	   $result     &= $this->Execute($this->mSqlQueries['do_insert_data'], array(
	      $param['nama']
	   ));

	   return $this->EndTrans($result);
	}
	public function getDataDetilKelompok($id)
	{
	   $return     = $this->Open($this->mSqlQueries['get_data_detil_kelompok'], array(
	      $id
	   ));

	   return $return[0];
	}

	public function doUpdateKelompok($param = array())
	{
	   $result     = true;
	   $this->StartTrans();
	   if(!is_array($param)){
	      $result  &= false;
	   }

	   $result     &= $this->Execute($this->mSqlQueries['do_update_data'], array(
	      $param['nama'],
	      $param['id']
	   ));

	   return $this->EndTrans($result);
	}


	public function doDeleteKelompok($id)
	{
	   $result     = true;
	   $this->StartTrans();
	   if(!$id){
	      $result  &= false;
	   }

	   $result     &= $this->Execute($this->mSqlQueries['do_delete_data'], array(
	      $id
	   ));

	   return $this->EndTrans($result);
	}
}

?>