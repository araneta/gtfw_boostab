<?php 


class Anggota extends Database
{
   # internal variables
   protected $mSqlFile;
   public $_POST;
   public $_GET;
   # Constructor
   function __construct ($connectionNumber = 0)
   {
      $db_type    = Configuration::Instance()->GetValue(
         'application',
         'db_conn',
         0,
         'db_type'
      );
      $this->mSqlFile   = 'module/'.Dispatcher::Instance()->mModule.'/business/'.$db_type.'/anggota.sql.php';
      $this->_POST      = is_object($_POST) ? $_POST->AsArray() : $_POST;
      $this->_GET       = is_object($_GET) ? $_GET->AsArray() : $_GET;
      parent::__construct($connectionNumber);
   }

   public function getKelompok()
   {
      $return     = $this->Open($this->mSqlQueries['get_kelompok'], array());

      return $return;
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

   public function getDataAnggota($offset, $limit, $param = array())
   {
      $return     = $this->Open($this->mSqlQueries['get_data_anggota'], array(
         '%'.$param['nama'].'%',
         $param['kelompok'],
         (int)($param['kelompok'] == '' OR strtolower($param['kelompok']) == 'all'),
         $offset,
         $limit
      ));

      return $return;
   }

   public function doInsertAnggota($param = array())
   {
      $result     = true;
      $this->StartTrans();
      if(!is_array($param)){
         $result  &= false;
      }

      $result     &= $this->Execute($this->mSqlQueries['do_insert_data_anggota'], array(
         $param['nama'],
         $param['email'],
         $param['phone'],
         $param['alamat'],
         $param['kelompok_id']
      ));

      return $this->EndTrans($result);
   }

   public function getDetilAnggota($id)
   {
      $return     = $this->Open($this->mSqlQueries['get_detail_anggota'], array(
         $id
      ));

      return $return[0];
   }

   public function doUpdateAnggota($param = array())
   {
      $result     = true;
      $this->StartTrans();
      if(!is_array($param)){
         $result  &= false;
      }

      $result     &= $this->Execute($this->mSqlQueries['do_update_data_anggota'], array(
         $param['nama'],
         $param['email'],
         $param['phone'],
         $param['alamat'],
         $param['kelompok_id'],
         $param['id']
      ));

      return $this->EndTrans($result);
   }

   public function doDeleteAnggota($id)
   {
      $result     = true;
      $this->StartTrans();
      if(!$id){
         $result  &= false;
      }

      $result     &= $this->Execute($this->mSqlQueries['do_delete_data_anggota'], array(
         $id
      ));

      return $this->EndTrans($result);
   }
}


 ?>