<?php

namespace bot\api\model;
include_once('bot/api/model.php');
use bot\api\model;
/**
 * 
 */
class message extends model
{
	public function getUserData($usTelId,$data)
	{
		$sql = "SELECT `$data` FROM `users` WHERE `telegram_id` = '$usTelId'";
		$us = mysqli_query($this->db,$sql);
		$name = mysqli_fetch_assoc($us);
		return $name[$data];
	}
	public function setUserData($usTelId,$that,$data)
	{
		$sql = "UPDATE `users` SET `$that` = '$data' WHERE `telegram_id` = '$usTelId'";
		$us = mysqli_query($this->db,$sql);
	}

	public function getCommand($command)
	{
		$sql = "SELECT `c`.`command_text`,`f`.`function_name`,`r`.`return_data`,`r`.`return_type`, `m`.`name` as `module_name` FROM `commands` as `c` LEFT JOIN `function_procedure` as `fp` on `c`.`procedure_id` = `fp`.`procedure_id` LEFT JOIN `functions` as `f` on `f`.`function_id` = `fp`.`function_id` LEFT JOIN `returns` as `r` on `r`.`return_id` = `fp`.`return_id` LEFT JOIN `modules` as `m` on `m`.`module_id` = `f`.`module_id` WHERE `command_text` = '$command'";
		$command = mysqli_query($this->db,$sql);
		$command = mysqli_fetch_assoc($command);
		return $command;
	}

	public function getCommandForAllMessages($command)
	{
		$sql = "SELECT `c`.`command_text`,`f`.`function_name`,`r`.`return_data`,`r`.`return_type`, `m`.`name` as `module_name` FROM `commands` as `c` LEFT JOIN `function_procedure` as `fp` on `c`.`procedure_id` = `fp`.`procedure_id` LEFT JOIN `functions` as `f` on `f`.`function_id` = `fp`.`function_id` LEFT JOIN `returns` as `r` on `r`.`return_id` = `fp`.`return_id` LEFT JOIN `modules` as `m` on `m`.`module_id` = `f`.`module_id` WHERE `command_type` = 'all_messages'";
		$command = mysqli_query($this->db,$sql);
		$command = mysqli_fetch_assoc($command);
		return $command;
	}
	
	public function getCommandForNewChatParticipant($command)
	{
		$sql = "SELECT `c`.`command_text`,`f`.`function_name`,`r`.`return_data`,`r`.`return_type`, `m`.`name` as `module_name` FROM `commands` as `c` LEFT JOIN `function_procedure` as `fp` on `c`.`procedure_id` = `fp`.`procedure_id` LEFT JOIN `functions` as `f` on `f`.`function_id` = `fp`.`function_id` LEFT JOIN `returns` as `r` on `r`.`return_id` = `fp`.`return_id` LEFT JOIN `modules` as `m` on `m`.`module_id` = `f`.`module_id` WHERE `command_type` = 'new_user'";
		$command = mysqli_query($this->db,$sql);
		$command = mysqli_fetch_assoc($command);
		return $command;
	}

	public function getButtonsAsCommand($command)
	{
		$sql = "SELECT `b`.`button_name` as `command_text`,`f`.`function_name`,`r`.`return_data`,`r`.`return_type`, `m`.`name` as `module_name` FROM `buttons` as `b` LEFT JOIN `function_procedure` as `fp` on `b`.`procedure_id` = `fp`.`procedure_id` LEFT JOIN `functions` as `f` on `f`.`function_id` = `fp`.`function_id` LEFT JOIN `returns` as `r` on `r`.`return_id` = `fp`.`return_id` LEFT JOIN `modules` as `m` on `m`.`module_id` = `f`.`module_id` WHERE `button_name` = '$command'";
		$buttons = mysqli_query($this->db,$sql);
		$button = mysqli_fetch_assoc($buttons);
		return $button;
	}
}
?>