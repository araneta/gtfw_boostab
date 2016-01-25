<?php
/**
* @author Prima Noor 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/
 
class Menu extends Database
{

    function __construct($connectionNumber = 0)
    {
        parent::__construct($connectionNumber);
        $this->LoadSql('module/gtfw_menu/business/mysqlt/menu.sql.php');
        $this->SetDebugOn();
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
        $input      = array(Configuration::Instance()->GetValue('application', 'application_id'));
        if (!empty($name)) {
            $filter .= " AND m.MenuName LIKE (%s)";
            $input[] = "%$name%";
        }
        $limit = '';
        if (!empty($display)){
        	$limit = "LIMIT $start, $display";
        }
        $query = $this->mSqlQueries['get_data'];
        $query = str_replace('--filter--', $filter, $query);
        $query = str_replace('--limit--', $limit, $query);
        $result = $this->Open($query, $input);
        return $result;

    }

    function getDetail($id)
    {
        $result = $this->Open($this->mSqlQueries['get_detail'], array($id));
        if ($result) {
            return $result[0];
        }
    }

    public function listMenu()
    {
    	return $this->Open($this->mSqlQueries['list_menu'], array());
    }

    public function add($params)
    {
    	return $this->Execute($this->mSqlQueries['add_menu'], $params);
    }

    public function edit($params)
    {
    	return $this->Execute($this->mSqlQueries['edit_menu'], $params);
    }
}

?>