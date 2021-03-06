<?php
$sql['get_data'] = "
SELECT SQL_CALC_FOUND_ROWS
    m.UserId AS `user_id`,
    #m.parent_user_id AS parent_id,
    m.UserName AS 'user_name',
   m.Realname AS 'real_name',
   m.Active AS active,
    dg.GroupId AS group_id,
    g.GroupName AS group_name,
    UnitName AS unit_name
FROM
    gtfw_user m
LEFT JOIN gtfw_user_def_group dg ON m.UserId = dg.UserId
LEFT JOIN gtfw_group g ON dg.GroupId = g.GroupId
LEFT JOIN gtfw_unit_application ua ON ua.unitappId = g.UnitappId
LEFT JOIN gtfw_unit ON unitappUnitId = UnitId
   --filter--
--limit--
";
$sql['count_data'] = "
SELECT FOUND_ROWS() AS total
";
//===GET===
//OK
$sql['get_count_data_user'] = "
   SELECT 
      COUNT(u.UserId) AS total
   FROM 
      gtfw_user u
      LEFT JOIN gtfw_user_def_group dg ON u.UserId = dg.UserId
      LEFT JOIN gtfw_group g ON dg.GroupId = g.GroupId
      LEFT JOIN gtfw_unit_application ua ON ua.unitappId = g.UnitappId
      LEFT JOIN gtfw_unit ON unitappUnitId = UnitId
   WHERE
      unitappApplicationId = '%s' AND
      UserName like '%s' AND
      RealName like '%s'
";

//OK
$sql['get_data_user'] = "
   SELECT 
      u.UserId AS user_id,
      u.UserName AS user_name,
      u.RealName AS real_name,
      u.Description AS description,
      u.Active AS is_active,
      dg.GroupId AS group_id,
      g.GroupName AS group_name,
      UnitName AS unit_name/*,
      unitappApplicationId*/
   FROM
      gtfw_user u
      LEFT JOIN gtfw_user_def_group dg ON u.UserId = dg.UserId
      LEFT JOIN gtfw_group g ON dg.GroupId = g.GroupId
      LEFT JOIN gtfw_unit_application ua ON ua.unitappId = g.UnitappId
      LEFT JOIN gtfw_unit ON unitappUnitId = UnitId
   WHERE
      unitappApplicationId = '%s' AND
      UserName like '%s' %s
      RealName like '%s'
   ORDER BY 
      UserName, RealName
   LIMIT %s, %s
";

//OK
$sql['get_data_user_by_id'] = "
   SELECT 
      u.UserId AS user_id,
      u.UserName AS user_name,
      u.RealName AS real_name,
      u.Description AS description,
      u.Active AS is_active,
      dg.GroupId AS group_id,
      g.GroupName AS group_name,
      UnitName AS unit_name,
      UnitId AS unit_kerja_id
   FROM
      gtfw_user u
      LEFT JOIN gtfw_user_def_group dg ON u.UserId = dg.UserId
      LEFT JOIN gtfw_group g ON dg.GroupId = g.GroupId
      LEFT JOIN gtfw_unit_application ua ON ua.unitappId = g.UnitappId
      LEFT JOIN gtfw_unit ON unitappUnitId = UnitId
   WHERE
      u.UserId = '%s'
";
//OK
$sql['get_count_duplicate_username'] = "
   SELECT 
      COUNT(*) AS COUNT
   FROM
      gtfw_user
   WHERE
      UserName = '%s'
      AND UserId != '%s'
";
//OK
$sql['get_count_duplicate_username_add'] = "
   SELECT 
      COUNT(*) AS COUNT
   FROM
      gtfw_user
   WHERE
      UserName = '%s'
";
   
//OK
$sql['get_combo_unit_kerja'] = "
   SELECT 
      UnitId AS id,
      UnitName AS name
   FROM gtfw_unit
   INNER JOIN gtfw_unit_application ON UnitId = unitappUnitId
   WHERE unitappApplicationId = '%s'
";

//OK
$sql['get_data_group_by_unit_id'] = "
   SELECT 
      GroupId AS id,
      GroupName AS name,
      Description AS group_description
   FROM 
      gtfw_group g
      INNER JOIN gtfw_unit_application ua ON g.UnitappId = ua.unitappId
   WHERE
      GroupName LIKE '%s' AND unitappUnitId = '%s' AND unitappApplicationId = '%s'
   ORDER BY 
      GroupName
";

//===DO===
//OK
$sql['do_add_user_def_group'] = "
   INSERT INTO gtfw_user_def_group
   SET
      UserId = %s,
      GroupId = %s,
      ApplicationId = %s
";
//OK
$sql['do_add_user_group'] = "
   INSERT INTO gtfw_user_group
   SET
      UserId = %s,
      GroupId = %s
";

//OK
$sql['do_update_user_def_group'] = "
   UPDATE gtfw_user_def_group
   SET
      GroupId = %s,
      ApplicationId = %s
   WHERE
      UserId = '%s'
";
//OK
$sql['do_update_user_group'] = "
   UPDATE gtfw_user_group
   SET
      GroupId = %s
   WHERE
      UserId = %s
";
//OK
$sql['do_add_user'] = "
   INSERT INTO gtfw_user
      (UserName, Password, RealName, Description, Active)
   VALUES 
      ('%s', md5('%s'), '%s', '%s', '%s')
";
//OK
$sql['do_update_user'] = 
   "UPDATE gtfw_user
   SET 
      UserName='%s',
      RealName = '%s', 
      Active='%s', 
      Description = '%s'
   WHERE 
      UserId=%s";
//OK
$sql['do_delete_user_by_id'] = 
   "DELETE from gtfw_user 
   WHERE 
      UserId=%s";
//OK
$sql['do_delete_user_by_array_id'] = 
   "DELETE from gtfw_user
   WHERE 
      UserId IN ('%s')";
//OK
$sql['do_update_password_user'] = 
   "UPDATE gtfw_user
   SET 
      Password=md5('%s')
   WHERE 
      UserId='%s'";

   //OK
   $sql['get_max_id'] = "
      SELECT MAX(UserId) AS id
      FROM gtfw_user
   ";
      
?>
