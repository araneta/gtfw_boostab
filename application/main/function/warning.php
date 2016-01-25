<?php
class Warning {
   static $warningMsg = array(
      'empty_input' => "Isian %s tidak boleh kosong.",
      'data_used' => "Data %s sudah digunakan, tidak dapat dihapus.",
      'reference_empty' => "<strong>Data Referensi %1\$s Belum Ada</strong><br />
            Silahkan mengisikan data %1\$s dahulu, agar bisa menggunakan fungsionalitas ini. ",
      'process_successed' => "Pengubahan data Berhasil Dilakukan.",
      'process_fail' => "Gagal Mengubah Data.",
      'data_add_successed' => "Penambahan data Berhasil Dilakukan.",
      'data_add_fail' => "Gagal Menambah Data.",
      'data_delete_successed' => "Penghapus data Berhasil Dilakukan.",
      'data_delete_fail' => "Gagal Menghapus Data.",
      'no_parameter' => "Kesalahan sistem, tidak ada parameter yang dikirim."
   );

   // contoh pemanggilan
   // Warning::SendAlert('empty_input', array($formName), $modul, $sub_modul);
   function SendAlert($alert, $param = array(), $modul, $sub_modul, $css='') {
      $strAlert = vsprintf(self::$warningMsg[$alert], $param);
      Messenger::Instance()->Send($modul, $sub_modul, 'view', 'html', array($_POST->AsArray(), $strAlert, $css),Messenger::NextRequest);
   }
   
   function SendCustomAlert($strAlert, $modul, $sub_modul, $css='') {
      if (is_array($strAlert)) {
         Messenger::Instance()->Send($modul, $sub_modul, 'view', 'html', array($_POST->AsArray(), vsprintf($strAlert[0], $strAlert[1]), $css),Messenger::NextRequest);
      } else {
         Messenger::Instance()->Send($modul, $sub_modul, 'view', 'html', array($_POST->AsArray(), $strAlert, $css),Messenger::NextRequest);
      }
   }
 }
 ?>