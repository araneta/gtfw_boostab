<?php
/** 
* @author Dyan Galih <Dyan.Galih@gmail.com>
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/
	
	class Notify extends Database
	{
		protected $mSqlFile;
	
		function __construct ($connectionNumber=0)
		{
			$this->mSqlFile = 'module/notify/business/notify.sql.php';
			#gtfwDebugSet
			parent::__construct($connectionNumber);
			#$this->setDebugOn();
		}
		#gtfwMethodOpen
		public function GetAllNotify(){
			$userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();
			$result = $this->Open($this->mSqlQueries['get_all_notify'], array($userId));
			$this->SetLoadAll();
			return $result;
		}
		
		#gtfwMethodOpen
		public function GetUnloadNotify(){
			#gtfwDebugSet
			#$this->SetDebugOn();
			$userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();
			$result = $this->Open($this->mSqlQueries['get_unload_notify'], array($userId));
			$this->SetLoadAll();
			return $result;
		}
		
		#gtfwMethodOpen
		public function GetModuleFromNotify($id){
			$result = $this->Open($this->mSqlQueries['get_module_from_notify'], array($id));
			return $result[0];
		}
		
		public function GetModuleId($module,$submodule){
			$result = $this->Open($this->mSqlQueries['get_module_id'], array($module,$submodule));
			return $result[0]['ModuleId'];
		}
		
		#gtfwMethodOpen
		public function SetLoadAll(){
			#gtfwDbExec
			return $this->execute($this->mSqlQueries['set_load_all'],array());
		}
		
		#gtfwMethodExecute
		public function setReadNotify($id){
			$result = $this->Execute($this->mSqlQueries['set_read_notify'], array($id));
			return $this->AffectedRows();
		}
		
		public function AddNotify($arrData){
			$query = $this->mSqlQueries['add_notify'];
			$idx=0;
			foreach($arrData as $value){
				if ($idx == 0) @$values .= $value;
				else $values .= ','.$value;
				$idx++;				
			}
			$query = sprintf($query,$values);
			$result = $this->Execute($query, array());
			return $this->AffectedRows();
		}
		
		public function DeleteNotifyByNomorSurat($nomorsurat){
			$result = $this->Execute($this->mSqlQueries['delete_notify_by_nomor_surat'], array('%'.$nomorsurat.'%'));
			return $result;
		}
	}
?>
