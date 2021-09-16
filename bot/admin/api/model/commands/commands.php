<?php

include_once $GLOBALS['PTH'].'api/ApiModel.php';
class commandsModel extends ApiModel
{

	public function get_all_commands()
	{
		$commands = [];
		$sql = "SELECT `c`.`command_text`,`c`.`command_type`,`c`.`command_id`,`f`.`function_name`,`f`.`function_id`,`r`.`return_data`,`r`.`return_type` FROM `commands` as `c` LEFT JOIN `function_procedure` as `fp` on `c`.`procedure_id` = `fp`.`procedure_id` LEFT JOIN `functions` as `f` on `f`.`function_id` = `fp`.`function_id` LEFT JOIN `returns` as `r` on `r`.`return_id` = `fp`.`return_id` LEFT JOIN `modules` as `m` on `m`.`module_id` = `f`.`module_id`";
		$commandsSql = mysqli_query($this->db,$sql);
		while ($commands[] = mysqli_fetch_assoc($commandsSql)) {}
		array_pop($commands);
		return $commands;
	}
	public function deleteCommands($ids)
	{
		$sql = "DELETE FROM `commands` WHERE `command_id` NOT IN (".$ids.")";
		// $this->debug();
		mysqli_query($this->db,$sql);
	}
	public function clearAll()
	{
		$sql = "DELETE FROM `function_procedure` WHERE `method` = 'commands';";
		mysqli_query($this->db,$sql);
		$sql = "DELETE FROM `returns` WHERE `method` = 'commands';"; 
		mysqli_query($this->db,$sql);
	}

	public function save($command,$command_id)
	{
		$retData = json_encode($command['return_data'],JSON_UNESCAPED_UNICODE);
		$retData = str_replace('"', '\"', $retData);
		$sql = "INSERT INTO `returns` (`return_id`, `return_type`, `return_data`,`method`) VALUES (NULL, 'text', '$retData','commands');";
		mysqli_query($this->db,$sql);
		$returnId = mysqli_insert_id($this->db);


		$sql = "SELECT * FROM `functions` WHERE `function_name` = '".$command['function_name']."'";
		$func = mysqli_query($this->db,$sql);
		$func = mysqli_fetch_assoc($func);

		$sql = "INSERT INTO `function_procedure` (`procedure_id`, `function_id`, `return_id`,`method`) VALUES (NULL, '".$func['function_id']."', '$returnId','commands');";
		mysqli_query($this->db,$sql);
		$procedureId = mysqli_insert_id($this->db);


		$sql = "SELECT * FROM `commands` WHERE `command_id` = '$command_id'";
		$commandsSql = mysqli_query($this->db,$sql);
		if($commandsSql->num_rows > 0)
		{
			$sql = "UPDATE `commands` SET `command_type` = '".$command['command_type']."',`command_text` = '".$command['command_text']."', `procedure_id` = '".$procedureId."' WHERE `command_id` = '$command_id'";
			mysqli_query($this->db,$sql);
		}else
		{
			$sql = "INSERT INTO `commands` (`command_id`, `command_text`, `procedure_id`,`command_type`) VALUES (NULL, '".$command['command_text']."', '".$procedureId."','".$command['command_type']."');";
			mysqli_query($this->db,$sql);
		}
	}
}
?>