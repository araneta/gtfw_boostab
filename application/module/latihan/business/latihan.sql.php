<?php 

$sql['get_pengguna'] = "
SELECT 
    UserId AS id,
    RealName AS realname,
    UserName AS username,
    Description AS `desc`,
    Active AS active,
    ForceLogout AS forcelogout
FROM
    gtfw_user 
";