<?php

/**
 * MYSQL 数据库驱动
 * 
 * @link http://www.yungengxin.com
 * @since v1.0
 * @author YunGengxin Dev Team
 */
class Mysql_MysqlUsePool extends Mysql_dbClient {

	const   LOG_LEVEL         = 1;                        //日志级别

    /**
     * @param $sql
     * @param string $page
     * @param string $pageSize
     * @param string $order
     * @return mixed
     */
	function select($sql, $page = '', $pageSize = '',  $order = '') {
		$tmpSql = $sql;
		if ($page) {
			$page = intval($page) <= 1 ? 1 : intval($page);
			$pageSize = intval($pageSize) ? intval($pageSize) : 20;

			$startNum = ($page - 1) * $pageSize;
			$tmpSql = "{$sql}" .
					($order ? " order by {$order} " : " ") .
					"limit {$startNum},{$pageSize} ";
		}
		return $this->query($tmpSql);
	}


    /**
     * @param $tableName
     * @param $array
     * @return bool
     */
	function insert($tableName, $array) {
		$res = $this->query("INSERT INTO $tableName(" . implode(',', array_keys($array)) . ") VALUES('" . implode("','", $array) . "')");
        return $res;
	}
	/**
	 * 删除数据
	 * 
	 * @param string $tablename 数据表
	 * @param string $where     条件语句
	 * @return bool             返回值：成功true，失败false
	 * @author zhangmh at 2014-6-10
	 */
	function delete($table, $where = '') {
		$whereStr = "";
		if ($where && is_array($where)) {
			foreach ($where as $key => $val) {
				$whereStr .= " and $key in('" . implode("','", explode(',', $val)) . "')";
			}
			$whereStr = substr($whereStr, 5);
		}
		if ($where && !is_array($where)) {
			$whereStr = $where;
		}

		$sql = "DELETE FROM  $table" . ($whereStr ? " WHERE $whereStr" : "");
		return $this->query($sql);
	}

    /**
     * @param $tableName
     * @param $values
     * @param string $where
     * @return bool
     */
	function update($tableName, $values, $where = '') {
		//SET中的列字段
		$valueStr = '';
		foreach ($values as $k => $v) {
			$valueStr .= ", $k='$v'";
		}

		//Where条件中的字段
		$whereStr = "";
		if ($where && is_array($where)) {
			foreach ($where as $key => $val) {
				$whereStr .= " and $key in('" . implode("','", explode(',', $val)) . "')";
			}
			$whereStr = substr($whereStr, 5);
		}
		if ($where && !is_array($where)) {
			$whereStr = $where;
		}

		$sql = "UPDATE $tableName SET " . substr($valueStr, 1) . ($whereStr ? " WHERE $whereStr" : "");
		return $this->query($sql);
	}

    /**
     * @param $sql
     * @return mixed
     */
	function query($sql) {
	    error_log($sql,3,'hpservermethod.log');
		$this->client->send($sql);
		return json_decode($this->client->recv(),true);
	}
}
