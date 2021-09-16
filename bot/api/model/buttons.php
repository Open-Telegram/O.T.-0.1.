<?php

namespace bot\api\model;
include_once('bot/api/model.php');
use bot\api\model;
/**
 * 
 */
class buttons extends model
{
	public function getButton($id)
	{
		$sql = "SELECT * FROM `buttons` WHERE `button_id` = '$id'";
		$but = mysqli_query($this->db,$sql);
		$button = mysqli_fetch_assoc($but);
		return $button;
	}

	public function getButtons()
	{
		$sql = "SELECT `$data` FROM `users` WHERE `telegram_id` = '$usTelId'";
		$us = mysqli_query($this->db,$sql);
		$name = mysqli_fetch_assoc($us);
		return $name[$data];
	}
}