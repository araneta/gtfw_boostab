<?php 
$sql['get_data'] = "
SELECT SQL_CALC_FOUND_ROWS
    m.MenuId AS `id`,
    m.MenuParentId AS parent_id,
    p.MenuName AS parent,
    m.MenuName AS `name`,
    m.IsShow AS is_show,
    m.IconPath AS icon,
    m.MenuOrder AS `order`
FROM
    gtfw_menu m
LEFT JOIN gtfw_menu p ON p.MenuId = m.MenuParentId
WHERE
	m.ApplicationId = %d
	--filter--
--limit--
";
$sql['count_data'] = "
SELECT FOUND_ROWS() AS total
";
$sql['get_detail'] = "
SELECT 
    m.MenuId AS `id`,
    m.MenuParentId AS parent_id,
    m.MenuName AS `name`,
    m.IsShow AS is_show,
    m.IconPath AS icon,
    m.MenuOrder AS `order`,
    m.MenuDefaultModuleId AS module_id
FROM
    gtfw_menu m
WHERE
	MenuId = %d
";
$sql['list_menu'] = "
SELECT 
    MenuId AS id,
    MenuName AS `name`
FROM
    gtfw_menu 
ORDER BY MenuName
";
$sql['add_menu'] = "
INSERT INTO gtfw_menu (
    MenuParentId,
    MenuName,
    IsShow,
    IconPath,
    MenuOrder,
    ApplicationId
) 
VALUES
    (
        %s,
        %s,
        %s,
        %s,
        %s,
        %s 
    )
";
$sql['edit_menu'] = "
UPDATE 
    gtfw_menu 
SET
    MenuParentId = %s,
    MenuName = %s,
    IsShow = %s,
    IconPath = %s,
    MenuOrder = %s,
    ApplicationId = %s 
WHERE MenuId = %s 
";