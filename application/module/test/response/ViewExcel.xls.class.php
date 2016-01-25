<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once GTFWConfiguration::GetValue('application', 'docroot').'module/test/business/Test.class.php';

class ViewExcel extends XlsResponse {
	var $mWorksheets = array('Data');
	var $mUseNewLibrary = true;

	function GetFileName() {
		return 'LaporanPengguna'.date("Y-m-d").'.xls';
	}

	function ProcessRequest()
	{
		// $Obj = new Test;

		// $data = $Obj->getPengguna();

		set_time_limit(0);
		for ($i=0; $i < 200; $i++) { 
            $data[$i]['realname']       = 'Prima Noor';
            $data[$i]['username']       = 'prima.noor';
            $data[$i]['desc']           = 'Prima Noor';
            $data[$i]['active']         = 'Yes';
            $data[$i]['forcelogout']    = 'no';
		}

		if (empty($data)) {
			$this->mWorksheets['Data']->write(0, 0, 'Data kosong');
		} else {

			$fTitle = $this->mrWorkbook->add_format();
			$fTitle->set_bold();
			$fTitle->set_size(12);
			$fTitle->set_align('vcenter');

			$fTitleSmall = $this->mrWorkbook->add_format();
			$fTitleSmall->set_size(10);
			$fTitleSmall->set_align('vcenter');

			$header = $this->mrWorkbook->add_format();
			$header->set_bold();
			$header->set_size(14);
			$header->set_align('vcenter'); 

			$fTableTitle = $this->mrWorkbook->add_format();
			$fTableTitle->set_border(1);
			$fTableTitle->set_bold();
			$fTableTitle->set_size(10);
			$fTableTitle->set_align('center');

			$fColData = $this->mrWorkbook->add_format();
			$fColData->set_border(1);

			$this->mWorksheets['Data']->write(0, 0, GTFWConfiguration::GetValue('organization', 'company_name'), $header);
			$this->mWorksheets['Data']->write(2, 0, 'Laporan Pengguna', $fTitle);

			$no = 5;
			$this->mWorksheets['Data']->write($no, 0, 'No', $fTableTitle);
			$this->mWorksheets['Data']->write($no, 1, 'Nama Asli', $fTableTitle);
			$this->mWorksheets['Data']->write($no, 2, 'Nama Pengguna', $fTableTitle);
			$this->mWorksheets['Data']->write($no, 3, 'Keterangan', $fTableTitle);
			$this->mWorksheets['Data']->write($no, 4, 'Aktif', $fTableTitle);
			$this->mWorksheets['Data']->write($no, 5, 'Force Logout', $fTableTitle);

			$no = 6;
			$data = $data;
			for($i=0;$i<sizeof($data);$i++){

				$this->mWorksheets['Data']->write($no, 0, ($i+1), $fColData);
				$this->mWorksheets['Data']->write($no, 1, $data[$i]['realname'], $fColData);
				$this->mWorksheets['Data']->write($no, 2, $data[$i]['username'], $fColData);
				$this->mWorksheets['Data']->write($no, 3, $data[$i]['desc'], $fColData);
				$this->mWorksheets['Data']->write($no, 4, $data[$i]['active'], $fColData);
				$this->mWorksheets['Data']->write($no, 5, $data[$i]['forcelogout'], $fColData);
				$no++;
			}
		}
	}
}
?>