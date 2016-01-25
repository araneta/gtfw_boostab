<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class AppUser extends Database {

   protected $mSqlFile= 'module/gtfw_user/business/mysqlt/appuser.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      #$this->setDebugOn();
   }
   //OK
   function GetDataUser ($offset, $limit, $userName='', $realName='', $applicationId) {		   
      if(($userName!='') and ($realName!=''))                      
         $str = ' OR ';
      else
         $str = ' AND ';
            
      $sql = sprintf($this->mSqlQueries['get_data_user'], $applicationId, '%s',$str,'%s','%d','%d');      
      $result = $this->Open($sql, array('%'.$userName.'%', '%'.$realName.'%', $offset, $limit));
      return $result;
   }
   //OK
   function GetDataUserById($id) {
      $result = $this->Open($this->mSqlQueries['get_data_user_by_id'], array($id));
      return $result;
   }
   //OK
   function GetCountDuplicateUsername($newUserName, $userId) {
      $result = $this->Open($this->mSqlQueries['get_count_duplicate_username'], array($newUserName, $userId));
      //
      return $result[0]['COUNT'];
   }
   //OK
   function GetCountDuplicateUsernameAdd($newUserName) {
      $result = $this->Open($this->mSqlQueries['get_count_duplicate_username_add'], array($newUserName));
      //
      return $result[0]['COUNT'];
   }
   //OK
   function GetCountDataUser ($userName='', $realName='', $applicationId) {
      $result = $this->Open($this->mSqlQueries['get_count_data_user'], array($applicationId, '%'.$userName.'%', '%'.$realName.'%'));
      if (!$result) {
         return 0;
      } else {
         return $result[0]['total'];
      }
   }
   //OK
   function GetMaxId(){
      $rs = $this->Open($this->mSqlQueries['get_max_id'], array());
      return $rs[0]['id'];
   }
   //OK
   function GetComboUnitKerja($applicationId){
      return $this->Open($this->mSqlQueries['get_combo_unit_kerja'],array($applicationId));
   }
   //OK
   function GetDataGroupByUnitId($groupName, $unitId, $applicationId) {
      $result = $this->Open($this->mSqlQueries['get_data_group_by_unit_id'], array('%'.$groupName.'%', $unitId, $applicationId));
      //
      return $result;
   }

//===DO==
   //OK
   function DoAddUserDefGroup($UserId, $GroupId, $ApplicationId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_user_def_group'], array($UserId, $GroupId, $ApplicationId));
      //
      return $result;
   }
   //OK
   function DoAddUserGroup($UserId, $GroupId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_user_group'], array($UserId, $GroupId));
      return $result;
   }
   //OK
   function DoUpdateUserDefGroup($GroupId, $ApplicationId, $UserId) {
      $result = $this->Execute($this->mSqlQueries['do_update_user_def_group'], array($GroupId, $ApplicationId, $UserId));
      //
      return $result;
   }
   //OK
   function DoUpdateUserGroup($GroupId, $UserId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_update_user_group'], array($GroupId, $UserId));
      //exit;
      return $result;
   }
   //OK
   function DoAddUser($userName, $password, $realName, $description, $active) {
      $result = $this->Execute($this->mSqlQueries['do_add_user'], array($userName, $password, $realName, $description, $active));
      //
      return $result;
   }
   //OK
   function DoUpdateUser($userName, $realName, $active, $decription, $userId) {
      $result = $this->Execute($this->mSqlQueries['do_update_user'], array($userName, $realName, $active, $decription, $userId));
      return $result;
   }
   //OK
   function DoDeleteUserById($userId) {
      $result=$this->Execute($this->mSqlQueries['do_delete_user_by_id'], array($userId));
      //
      return $result;
   }
   //OK
	function DoDeleteUserByArrayId($arrUserId) {
		$userId = implode("', '", $arrUserId);
		$result=$this->Execute($this->mSqlQueries['do_delete_user_by_array_id'], array($userId));
		return $result;
	}
   //tambahan
   //OK 
   function DoUpdatePasswordUser($password, $userId) {
      $result = $this->Execute($this->mSqlQueries['do_update_password_user'], array($password, $userId));
      return $result;
   }

   function countData()
    {
        $query = $this->mSqlQueries['count_data'];
        $result = $this->Open($query, array());
        return $result[0]['total'];
    }

    function getData($params)
    {
        if (is_array($params))
            extract($params);
        $filter     = '';

        if (!empty($name) && !empty($nama)) {
          $filter .= "WHERE m.UserName LIKE '%$name%' AND m.RealName LIKE '%$nama%'";

        }elseif(!empty($nama) && !empty($name)) {
          $filter .= "WHERE m.RealName LIKE '%$nama%' AND m.UserName LIKE '%$name%'";

        }elseif (!empty($name)) {
          $filter .= "WHERE m.UserName LIKE '%$name%'";
        }elseif (!empty($nama)) {
          $filter .= "WHERE m.RealName LIKE '%$nama%'";

        }else{
          $filter .= "";
        }

        $limit = '';
        if (!empty($display)){
         $limit = "LIMIT $start, $display";
        }
        $query = $this->mSqlQueries['get_data'];
        $query = str_replace('--filter--', $filter, $query);
        $query = str_replace('--limit--', $limit, $query);
        $result = $this->Open($query,array());
        return $result;
    }

}
?>
