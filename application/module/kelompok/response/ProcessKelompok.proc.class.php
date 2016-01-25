<?php
$db_type       = Configuration::Instance()->GetValue(
   'application',
   'db_conn',
   0,
   'db_type'
);
require_once GTFWConfiguration::GetValue('application','docroot').
'module/'.Dispatcher::Instance()->mModule.'/business/'.$db_type.'/Kelompok.class.php';

class ProcessKelompok
{
   private $mObj;
   protected $mData  = array();
   private $url_add;
   private $url_view;
   private $url_edit;
   function __construct ($connectionNumber = 0)
   {
      $this->mObj    = new Kelompok();
      $this->url_add = Dispatcher::Instance()->GetUrl(
         'kelompok',
         'AddKelompok',
         'view',
         'html'
      );
      $this->url_edit   = Dispatcher::Instance()->GetUrl(
         'kelompok',
         'EditKelompok',
         'view',
         'html'
      );
      $this->url_view   = Dispatcher::Instance()->GetUrl(
         'kelompok',
         'Kelompok',
         'view',
         'html'
      );
   }

   private function setData()
   {
      $request_data  = array();
      if(isset($this->mObj->_POST['btnsimpan'])){
         $request_data['id']     = $this->mObj->_POST['data_id'];
         $request_data['nama']   = trim($this->mObj->_POST['nama']);
      }

      $this->mData   = $request_data;
   }

   public function getData()
   {
      $this->setData();
      return (array)$this->mData;
   }

   public function Update()
	{
	   if(isset($this->mObj->_POST['btnbalik'])){
	      return $this->url_view;
	   }
	   $this->setData();
	   $check      = $this->checkData();

	   if($check['result'] === false){
	      Messenger::Instance()->Send(
	         'kelompok',
	         'EditKelompok',
	         'view',
	         'html',
	         array(
	            $this->mObj->_POST,
	            $check['message'],
	            'notebox-warning'
	         ),
	         Messenger::NextRequest
	      );

	      return $this->url_add;
	   }else{
	      $process       = $this->mObj->doUpdateKelompok($this->mData);
	      if($process === true){
	         Messenger::Instance()->Send(
	            'kelompok',
	            'Kelompok',
	            'view',
	            'html',
	            array(
	               NULL,
	               'Proses Update data berhasil',
	               'notebox-done'
	            ),
	            Messenger::NextRequest
	         );

	         return $this->url_view;
	      }else{
	         Messenger::Instance()->Send(
	            'kelompok',
	            'EditKelompok',
	            'view',
	            'html',
	            array(
	               $this->mObj->_POST,
	               'Proses Update data gagal',
	               'notebox-warning'
	            ),
	            Messenger::NextRequest
	         );

	         return $this->url_add;
	      }
	   }
	}

   private function checkData()
   {
      $request_data     = (array)$this->mData;
      if(empty($request_data)){
         $err[]   = 'Tidak ada data yang akan diproses';
      }

      if($request_data['nama'] == ''){
         $err[]   = 'Isikan Nama Kelompok';
      }

      // Check input data dengan data yang ada di dalam database
      $check_data    = $this->mObj->doCheckData(
         $request_data['nama'],
         $request_data['id']
      );

      if($check_data === false){
         $err[]   = 'Data dengan key '.$request_data['nama'].' Sudah terdaftar dalam database';
      }

      if(isset($err)){
         $return['result']    = false;
         $return['message']   = $err[0];
      }else{
         $return['result']    = true;
         $return['message']   = NULL;
      }

      return $return;
   }

   public function Save()
   {
      if(isset($this->mObj->_POST['btnbalik'])){
         return $this->url_view;
      }
      $this->setData();
      $check      = $this->checkData();

      if($check['result'] === false){
         Messenger::Instance()->Send(
            'kelompok',
            'AddKelompok',
            'view',
            'html',
            array(
               $this->mObj->_POST,
               $check['message'],
               'notebox-warning'
            ),
            Messenger::NextRequest
         );

         return $this->url_add;
      }else{
         $process       = $this->mObj->doInsertKelompok($this->mData);
         if($process === true){
            Messenger::Instance()->Send(
               'kelompok',
               'Kelompok',
               'view',
               'html',
               array(
                  NULL,
                  'Proses penambahan data berhasil',
                  'notebox-done'
               ),
               Messenger::NextRequest
            );

            return $this->url_view;
         }else{
            Messenger::Instance()->Send(
               'kelompok',
               'AddKelompok',
               'view',
               'html',
               array(
                  $this->mObj->_POST,
                  'Proses penambahan data gagal',
                  'notebox-warning'
               ),
               Messenger::NextRequest
            );

            return $this->url_add;
         }
      }
   }


	public function Delete()
	{
	   $id_delete     = $this->mObj->_POST['idDelete'];

	   if($id_delete){
	      $process       = $this->mObj->doDeleteKelompok($id_delete);
	      if($process === true){
	         $message    = 'Proses penghapusan data berhasil';
	         $style      = 'notebox-done';
	      }else{
	         $message    = 'Proses penghapusan data gagal';
	         $style      = 'notebox-warning';
	      }
	   }else{
	      $message    = 'Tidak ada data yang akan dihapus';
	      $style      = 'notebox-done';
	   }

	   Messenger::Instance()->Send(
	      'kelompok',
	      'Kelompok',
	      'view',
	      'html',
	      array(
	         NULL,
	         $message,
	         $style
	      ),
	      Messenger::NextRequest
	   );

	   return $this->url_view;
	}
}
?>