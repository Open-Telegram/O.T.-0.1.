<?php
/**
 * Created by VlAD for Cigitus company 24.10.2020
 */
include_once $GLOBALS['PTH'].'api/ApiModel.php';
class homeModel extends ApiModel
{

	public function getCountMessages()
	{
		$count = $this->dbSql("SELECT SUM(`message_count`) as `mCount` FROM `users`");
		return $count[0]['mCount'];
	}

	public function getCountUsers()
	{
		$count = $this->dbSql("SELECT COUNT(*) as `uCount` FROM `users`");
		return $count[0]['uCount'];
	}

	public function getCountButtons()
	{
		$count = $this->dbSql("SELECT COUNT(*) as `bCount` FROM `buttons`");
		return $count[0]['bCount'];
	}

	public function getCountCommands()
	{
		$count = $this->dbSql("SELECT COUNT(*) as `cCount` FROM `commands`");
		return $count[0]['cCount'];
	}
}
?>