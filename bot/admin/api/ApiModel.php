<?php
/**
 * Created by VlAD for Cigitus company 24.10.2020
 */
class ApiModel
{
	private $sqlDebug = false;
	public $db;
	public $conf;
	public function __construct()
	{
		if(empty($this->db))
		{
			$this->conf = include($GLOBALS['PTH'].'system/config.php');
			$this->db = mysqli_connect($this->conf['DB_host'],$this->conf['DB_log'],$this->conf['DB_pass'],$this->conf['DB_name']) or die('Ошибка подключения к БД');
		}
	}
	protected function sqlDebug()
	{
		 $this->sqlDebug = true;
	}


	function getGUID(){
	    if (function_exists("com_create_guid")){
	        return com_create_guid();
	    }else{
	        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
	        $charId = md5(uniqid(rand(), true));
	        $hyphen = chr(45);// "-"
	        $uuid =substr($charId, 0, 8).$hyphen
	        .substr($charId, 8, 4).$hyphen
	        .substr($charId,12, 4).$hyphen
	        .substr($charId,16, 4).$hyphen
	        .substr($charId,20,12);
	        return $uuid;
	    }
	}
	protected function dbSelect($table='',$where=[],$all='*',$asc=[],$limit = 500)
	{
		if(!empty($table))
		{
			$sql = "SELECT $all FROM `$table`";
			if(!empty($where))
			{
	 			$sql .= " WHERE ";
				foreach ($where as $key => $value) {
					switch ($key) {
						case 'LIKE':
							foreach ($value as $vKey => $valueLike) {
								$sql .= " `$vKey` LIKE '$valueLike' and";
							}
							break;
						case 'LIKE OR':
							foreach ($value as $vKey => $valueLike) {
								$sql .= " `$vKey` LIKE '$valueLike' OR";
							}
							break;
						case 'NOT LIKE':
							foreach ($value as $nvKey => $valueNLike) {
								$sql .= " `$nvKey` NOT LIKE '$valueNLike' and";
							}
							break;
						case 'NOT LIKE OR':
							foreach ($value as $nvKey => $valueNLike) {
								$sql .= " `$nvKey` NOT LIKE '$valueNLike' OR";
							}
							break;
						case '= OR':
							foreach ($value as $nvKey => $valueNLike) {
								$sql .= " `$key` = '$value' OR";
							}
							break;
						
						default:
							$sql .= " `$key` = '$value' and";
							break;
					}
				}
				$sql = substr($sql, 0, -3); 
			}

			if(!empty($asc))
			{
				$coll = $asc[0];
				$ascOrDesc = $asc[1];
				$sql .= " ORDER BY `$table`.`$coll` $ascOrDesc";
			}
			if(!empty($limit))
			{
				$sql .= " LIMIT $limit";
			}
			$answer = [];
			if($this->sqlDebug == true)
			{
				$this->debug($sql);
			}
			$answSql = mysqli_query($this->db,$sql);
			while($answer[] = mysqli_fetch_assoc($answSql)){}
			array_pop($answer);
			return $answer;
		}
	}

	protected function getLastInserId()
	{
		return $this->db->insert_id;
	}

	protected function dbSql($sql = '')
	{
		$answer = [];
		$answSql = mysqli_query($this->db,$sql);
		if($answSql->num_rows >= 1)
		{
			while($answer[] = mysqli_fetch_assoc($answSql)){}
			array_pop($answer);
		}
		return $answer;
	}

	protected function debug($sql)
	{
		echo '<br>';
		echo '<br>';
		echo '<br>';
		echo '<pre>';
		print_r($sql);
		echo '</pre>';
	}
}
?>