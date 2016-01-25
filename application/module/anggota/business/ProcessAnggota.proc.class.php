<?php 

$db_type       = Configuration::Instance()->GetValue(
   'application',
   'db_conn',
   0,
   'db_type'
);
require_once GTFWConfiguration::GetValue('application','docroot').
'module/'.Dispatcher::Instance()->mModule.'/business/'.$db_type.'/Anggota.class.php';

class ProcessAnggota
{
   # internal variables
   private $mObj;
   private $url_add;
   private $url_edit;
   private $url_view;
   # Constructor
   function __construct ($connectionNumber = 0)
   {
      $this->mObj       = new Anggota($connectionNumber);
      $this->url_view   = Dispatcher::Instance()->GetUrl(
         'anggota',
         'Anggota',
         'view',
         'html'
      );
      $this->url_add    = Dispatcher::Instance()->GetUrl(
         'anggota',
         'AddAnggota',
         'view',
         'html'
      );
      $this->url_edit   = Dispatcher::Instance()->GetUrl(
         'anggota',
         'EditAnggota',
         'view',
         'html'
      );
   }

   private function setData()
   {
      $request_data     = array();
      if(isset($this->mObj->_POST['btnsimpan'])){
         $request_data['id']     = $this->mObj->_POST['data_id'];
         $request_data['kelompok_id']  = $this->mObj->_POST['kelompok'];
         $request_data['nama']         = $this->mObj->_POST['nama'];
         $request_data['email']        = $this->mObj->_POST['email'];
         $request_data['phone']        = $this->mObj->_POST['phone'];
         $request_data['alamat']       = $this->mObj->_POST['alamat'];
      }

      $this->mData      = $request_data;
   }

   public function getData()
   {
      $this->setData();
      return $this->mData;
   }

   public function Save()
   {
      if(isset($this->mObj->_POST['btnbalik'])){
         return $this->url_view;
      }

      $this->setData();
      $process       = $this->mObj->doInsertAnggota($this->mData);
      if($process === true){
         Messenger::Instance()->Send(
            'anggota',
            'Anggota',
            'view',
            'html',
            array(
               NULL,
               'Proses input data berhasil',
               'notebox-done'
            ),
            Messenger::NextRequest
         );
         return $this->url_view;
      }else{
         Messenger::Instance()->Send(
            'anggota',
            'AddAnggota',
            'view',
            'html',
            array(
               $this->mObj->_POST,
               'Proses input data gagal',
               'notebox-warning'
            ),
            Messenger::NextRequest
         );
         return $this->url_add;
      }

   }

   public function Update()
   {
      if(isset($this->mObj->_POST['btnbalik'])){
         return $this->url_view;
      }

      $this->setData();
      $process       = $this->mObj->doUpdateAnggota($this->mData);
      if($process === true){
         Messenger::Instance()->Send(
            'anggota',
            'Anggota',
            'view',
            'html',
            array(
               NULL,
               'Proses update data berhasil',
               'notebox-done'
            ),
            Messenger::NextRequest
         );
         return $this->url_view;
      }else{
         Messenger::Instance()->Send(
            'anggota',
            'EditAnggota',
            'view',
            'html',
            array(
               $this->mObj->_POST,
               'Proses update data gagal',
               'notebox-warning'
            ),
            Messenger::NextRequest
         );
         return $this->url_edit;
      }

   }

   public function Delete()
   {
      $id_delete     = $this->mObj->_POST['idDelete'];

      if($id_delete){
         $process       = $this->mObj->doDeleteAnggota($id_delete);
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
         'anggota',
         'Anggota',
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