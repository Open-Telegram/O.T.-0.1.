<?php
/**
 * Created by VlAD for Cigitus company 24.10.2020
 */
include_once $GLOBALS['PTH'].'api/ApiModel.php';
class buttonsModel extends ApiModel
{
	public function get_all_buttons()
	{
		$sql = "SELECT `b`.`button_name`,`b`.`button_id`,`f`.`function_name`,`f`.`function_id`,`r`.`return_data`,`r`.`return_type` FROM `buttons` as `b` LEFT JOIN `function_procedure` as `fp` on `b`.`procedure_id` = `fp`.`procedure_id` LEFT JOIN `functions` as `f` on `f`.`function_id` = `fp`.`function_id` LEFT JOIN `returns` as `r` on `r`.`return_id` = `fp`.`return_id` LEFT JOIN `modules` as `m` on `m`.`module_id` = `f`.`module_id`";
		$buttonsSql = mysqli_query($this->db,$sql);
		while ($buttons[] = mysqli_fetch_assoc($buttonsSql)) {}
		array_pop($buttons);
		return $buttons;
	}

	public function deleteButtons($ids)
	{
		$sql = "DELETE FROM `buttons` WHERE `button_id` NOT IN (".$ids.")";
		mysqli_query($this->db,$sql);
	}

	public function clearAll()
	{
		$sql = "DELETE FROM `function_procedure` WHERE `method` = 'buttons';";
		mysqli_query($this->db,$sql);
		$sql = "DELETE FROM `returns` WHERE `method` = 'buttons';"; 
		mysqli_query($this->db,$sql);
	}

	public function save($button,$button_id)
	{
		$retData = json_encode($button['return_data'],JSON_UNESCAPED_UNICODE);
		$retData = str_replace('"', '\"', $retData);
		$sql = "INSERT INTO `returns` (`return_id`, `return_type`, `return_data`,`method`) VALUES (NULL, 'text', '$retData','buttons');";
		mysqli_query($this->db,$sql);
		$returnId = mysqli_insert_id($this->db);


		$sql = "SELECT * FROM `functions` WHERE `function_name` = '".$button['function_name']."'";
		$func = mysqli_query($this->db,$sql);
		$func = mysqli_fetch_assoc($func);

		$sql = "INSERT INTO `function_procedure` (`procedure_id`, `function_id`, `return_id`,`method`) VALUES (NULL, '".$func['function_id']."', '$returnId','buttons');";
		mysqli_query($this->db,$sql);
		$procedureId = mysqli_insert_id($this->db);


		$sql = "SELECT * FROM `buttons` WHERE `button_id` = '$button_id'";
		$buttonsSql = mysqli_query($this->db,$sql);
		if($buttonsSql->num_rows > 0)
		{
			$sql = "UPDATE `buttons` SET `button_name` = '".$button['button_name']."', `procedure_id` = '".$procedureId."' WHERE `button_id` = '$button_id'";
			mysqli_query($this->db,$sql);
		}else
		{
			$sql = "INSERT INTO `buttons` (`button_id`, `button_name`, `procedure_id`) VALUES (NULL, '".$button['button_name']."', '".$procedureId."');";
			mysqli_query($this->db,$sql);
		}
	}
}
?>