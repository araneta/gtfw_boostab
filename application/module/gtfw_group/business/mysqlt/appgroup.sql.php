<?php
//===GET===

$sql['get_data_group'] = 
   "SELECT 
      GroupId AS id,
      GroupName AS groupname,
      g.Description AS description,
      g.UnitappId AS group_unit_id,
      gu.UnitName AS unit_kerja
   FROM 
      gtfw_group g
      JOIN gtfw_unit_application ut ON ut.unitappId=g.UnitappId
		LEFT JOIN gtfw_unit gu ON unitappUnitId = UnitId
   WHERE
      GroupName like '%s' 
   /*AND 
      GroupId != '2'*/
   ORDER BY 
      GroupName";

$sql['get_data_group_by_unit_id'] = 
   "SELECT 
      GroupId AS id,
      GroupName AS groupname,
      g.Description AS description,
      g.UnitId AS group_unit_id,
      ut.UnitName AS unit_kerja
   FROM 
      gtfw_group g
      JOIN gtfw_unit ut ON ut.UnitId=g.UnitId
   WHERE
      GroupName like '%s' and g.UnitId = '%s'
   /*AND 
      GroupId != '2'*/
   ORDER BY 
      GroupName";

/*
$sql['get_data_group_with_privilege'] = 
   "SELECT 
      g.GroupId AS group_id,
      g.GroupName AS groupname,
      g.Description AS description,
      g.UnitId AS group_unit_id,
      ut.UnitName AS unit_kerja,
      GROUP_CONCAT(`MenuId` ORDER BY `MenuName` SEPARATOR '|') AS `menu_id`,
      GROUP_CONCAT(`MenuName` ORDER BY `MenuName` SEPARATOR '|') AS `menu_name`,
      GROUP_CONCAT(`ParentMenuId` ORDER BY `MenuName` SEPARATOR '|') AS `parent_menu`
   FROM 
      gtfw_group g
      JOIN gtfw_unit ut ON ut.UnitId=g.UnitId
      LEFT JOIN gtfw_group_menu gm ON g.GroupId=gm.GroupId
   WHERE
      GroupName like '%s' #AND g.GroupId>2
   GROUP BY g.GroupId
   ORDER BY GroupName";
*/

$sql['get_data_group_with_privilege'] = "
SELECT 
      g.GroupId AS group_id, 
      g.GroupName AS group_name, 
      g.Description AS group_description, 
      gu.UnitId AS group_unit_id, 
      gu.UnitName AS unit_kerja, 
      gm.`MenuName` AS `menu_name`, 
      gm.subMenuName AS sub_menu
   FROM 
      gtfw_group g
      JOIN gtfw_unit_application ut ON ut.unitappId=g.unitappId
      JOIN gtfw_unit gu ON ut.unitappUnitId = gu.unitId
      LEFT JOIN(
	SELECT
		a.GroupId,
		a.MenuName,
		b.MenuName AS subMenuName
	FROM gtfw_group_menu a 
	LEFT JOIN gtfw_group_menu b ON a.MenuId = b.ParentMenuId
	WHERE a.ParentMenuId = 0
	ORDER BY a.MenuName, b.MenuName
      ) gm ON g.GroupId=gm.GroupId
   WHERE
      GroupName like '%s'
	AND 
		ut.unitappApplicationId = '%s'
   ORDER BY GroupName, MenuName
";
   
$sql['get_data_group_by_id']= 
   "SELECT 	
	   g.GroupId AS group_id,
      g.GroupName AS groupname,
      g.Description AS description,
      UnitId AS group_unit_id,
      UnitName AS unit_kerja,
      GROUP_CONCAT(`MenuId` ORDER BY `MenuName` SEPARATOR '|') AS `menu_id`,
      GROUP_CONCAT(`MenuName` ORDER BY `MenuName` SEPARATOR '|') AS `menu_name`,
      GROUP_CONCAT(`ParentMenuId` ORDER BY `MenuName` SEPARATOR '|') AS `parent_menu`
   FROM 
    	gtfw_group g
    	JOIN gtfw_unit_application ut ON ut.unitappId=g.UnitappId
		LEFT JOIN gtfw_unit ON unitappUnitId = UnitId
    	LEFT JOIN gtfw_group_menu gm ON g.GroupId=gm.GroupId
   WHERE
	    g.GroupId = '%s'
   GROUP BY g.GroupId";

$sql['get_last_group_id'] = 
   "SELECT MAX(GroupId) 
   FROM gtfw_group;";

$sql['is_can_access_menu'] = "
   SELECT
      count(*) as result
   FROM
      gtfw_group_menu m
   WHERE 
      MenuName='%s'
   AND groupId='%s'";
   
$sql['get_all_privilege'] = 
   "SELECT DISTINCT
  	  m.MenuId AS menu_id,
  	  m.MenuName AS menu_name,
  	  m.MenuParentId AS menu_parent_id,
  	  m.MenuDefaultModuleId as default_module_id,
	  m.IsShow as is_show,
	  a.MenuName
   FROM 
      gtfw_menu m
   LEFT JOIN(
      SELECT 	
         MenuName
      FROM 
       	gtfw_group g
       	LEFT JOIN gtfw_group_menu gm ON g.GroupId=gm.GroupId
      WHERE
   	    g.GroupId = '%s'
   ) a ON m.MenuName= a.MenuName
       WHERE 
		 	m.IsShow = 'Yes'
		 AND 
		 	m.ApplicationId = '%s'
      ORDER BY MenuParentId, m.MenuName";

$sql['get_group_privilege'] = 
   "SELECT 
      gm.MenuId AS menu_id,
      gm.MenuName AS menu_name,
      gm.ParentMenuId AS parent_menu_id,  
      m.MenuDefaultModuleId AS module_id
   FROM 
      gtfw_group g
      JOIN gtfw_group_menu gm ON g.groupId=gm.groupId
      JOIN gtfw_menu m ON MenuMenuId = m.MenuId
   WHERE 
      parentMenuId != 0 AND
      gm.groupId = '%s'";

$sql['get_privilege_by_id'] = 
    "SELECT 
      MenuId AS menu_id,
      MenuName AS menu_name,
      MenuParentId AS menu_parent_id,
      MenuDefaultModuleId as default_module_id,
      IsShow as is_show 
   FROM 
      gtfw_menu
   WHERE 
      MenuId = '%s'";

$sql['get_privilege_by_array_id'] = 
    "SELECT 
      MenuId AS menu_id,
      MenuName AS menu_name,
      MenuParentId AS menu_parent_id,
      MenuDefaultModuleId as default_module_id,
      IsShow as is_show 
   FROM 
      gtfw_menu
   WHERE 
      MenuId IN (%s)";

$sql['get_max_menu_id']= "
   SELECT 
      MAX(MenuId) as max_id
   FROM
      gtfw_group_menu
   ";

$sql['get_data_group_menu'] = 
   "SELECT 
      MenuId AS menu_id,
      MenuName AS menu_name, 
      GroupId AS group_id 
	FROM 
      gtfw_group_menu
   WHERE 
     parentMenuId != 0";  
     
$sql['get_data_group_menu_by_group_id'] = 
   "SELECT 
      MenuId AS menu_id,
      MenuName AS menu_name, 
      GroupId AS group_id 
	FROM 
      gtfw_group_menu
   WHERE 
     ParentMenuId != 0 and GroupId='%s'";

$sql['get_combo_unit_kerja'] = "
   SELECT 
      a.UnitId AS id,
      a.UnitName AS name
	FROM 
		gtfw_unit a
	LEFT JOIN gtfw_unit_application b ON a.UnitId = b.unitappUnitId
	WHERE b.unitappApplicationId = '%s'
";

$sql['get_all_aksi_by_group_id']="
SELECT a.MenuId,GROUP_CONCAT(a.ModuleId SEPARATOR '|') AS ModuleId,a.LabelAksi,b.AksiLabel,(SELECT COUNT(c.ModuleId) FROM gtfw_group_module c WHERE c.ModuleId = a.ModuleId AND c.GroupId = '%s') AS Checked
FROM gtfw_module a
LEFT JOIN gtfw_aksi b ON b.AksiId = a.AksiId
WHERE a.MenuId IS NOT NULL
GROUP BY a.MenuId,a.LabelAksi
ORDER BY a.AksiId
";

$sql['GetValueAksi']="
SELECT
	configValue AS VALUE
FROM gtfw_config
WHERE configName = 'cfg_hak_akses_per_aksi'
";

//===DO===
$sql['do_add_user_def_group'] = "
   INSERT INTO gtfw_user_def_group
   SET
      UserId = %s,
      GroupId = %s,
      ApplicationId = %s
";

$sql['do_add_user_group'] = "
   INSERT INTO gtfw_user_group
   SET
      UserId = %s,
      GroupId = %s
";

$sql['do_add_group'] = 
   "INSERT INTO gtfw_group 
      (GroupName, Description,UnitappId)
   VALUES
      ('%s','%s',(SELECT unitappId FROM gtfw_unit_application WHERE unitappUnitId = '%s' AND unitappApplicationId = '%s'))";

$sql['do_update_user_def_group'] = "
   UPDATE gtfw_user_def_group
   SET
      GroupId = %s,
      ApplicationId = %s
   WHERE
      UserId = '%s'
";

$sql['do_update_user_group'] = "
   UPDATE gtfw_user_group
   SET
      GroupId = %s
   WHERE
      UserId = %s
";
 
$sql['do_update_group'] =
   "UPDATE gtfw_group
   SET 
      GroupName='%s',
      Description='%s',
      UnitappId=(SELECT unitappId FROM gtfw_unit_application WHERE unitappUnitId = '%s' AND unitappApplicationId = '%s')
   WHERE 
      GroupId='%s'";
   
$sql['do_delete_group'] = 
   "DELETE FROM gtfw_group
   WHERE GroupId = '%s'";

$sql['do_add_privilege'] = 
   "INSERT INTO gtfw_user
      (UserName, Password, RealName, Decription, Active, GroupId)
   VALUES 
      ('%s', md5('%s'), '%s', '%s', 'Yes', '%s')";
      
$sql['do_add_group_menu_for_new_group'] = 
   "INSERT INTO gtfw_group_menu
      (MenuName, GroupId, ParentMenuId, MenuMenuId)
   SELECT 
      '%s', MAX(GroupId), '%s', '%s'
      FROM gtfw_group";
      
$sql['do_add_group_menu'] = 
   "INSERT INTO gtfw_group_menu
      (MenuName, GroupId, ParentMenuId, MenuMenuId)
   VALUES 
      ('%s', '%s', '%s', '%s')";
      
$sql['do_add_group_module_by_module_name_new_group'] = "
   INSERT INTO 
      gtfw_group_module
   SELECT MAX(g.GroupId), m.ModuleId
      FROM gtfw_module m, gtfw_group g
      WHERE module='%s'
      GROUP BY ModuleId ";
      
$sql['do_add_group_module_by_module_name'] = "
   INSERT INTO 
      gtfw_group_module
   SELECT %d, m.ModuleId
      FROM gtfw_module m
      WHERE module='%s'
      GROUP BY ModuleId ";
      
$sql['do_add_group_module_from_gtfw_menu_new_group'] = "
   INSERT INTO 
      gtfw_group_module
   SELECT
      MAX(g.GroupId), m.ModuleId
   FROM
      gtfw_module m, gtfw_group g
   WHERE m.MenuId='%s' [SQL_WHERE_2]
   GROUP BY m.ModuleId
";
      
$sql['do_add_group_module_from_gtfw_menu'] = "
   INSERT INTO 
      gtfw_group_module
   SELECT
      '%s', a.ModuleId
   FROM gtfw_module a
   WHERE a.MenuId='%s' [SQL_WHERE_1]
";

$sql['do_add_group_module'] = "
   INSERT INTO gtfw_group_module
   SET
      GroupId = '%s',
      ModuleId = (
         SELECT
            ModuleId
         FROM gtfw_module
         WHERE Module = 'home'
         AND ApplicationId = '%s'
      )
";
      
$sql['do_add_group_module_newgroup'] =
   "INSERT INTO gtfw_group_module
      (GroupId, ModuleId)
   SELECT MAX(GroupId), '%s'
      FROM gtfw_group";

$sql['do_add_group_module_newgroup'] = "
   INSERT INTO gtfw_group_module
   SET
      GroupId = (
         SELECT MAX(GroupId)
         FROM gtfw_group
      ),
      ModuleId = (
         SELECT
            ModuleId
         FROM gtfw_module
         WHERE Module = 'home'
         AND ApplicationId = '%s'
      )
";

$sql['do_add_group_module2'] =
   "INSERT INTO gtfw_group_module
      (GroupId, ModuleId)
   VALUES
      ('%s', '%s')";
      
$sql['do_add_group_module_newgroup2'] =
   "INSERT INTO gtfw_group_module
      (GroupId, ModuleId)
   SELECT MAX(GroupId), '%s'
      FROM gtfw_group";

# --- delete ---


$sql['do_delete_group_menu'] =
   "DELETE 
   FROM 
      gtfw_group_menu
   WHERE 
      GroupId='%s'";

$sql['do_delete_group_module']  = 
   "DELETE 
   FROM 
      gtfw_group_module
   WHERE 
      GroupId='%s'";

?>
