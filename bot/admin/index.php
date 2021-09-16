<?php
if(file_exists('system/config.php'))
{
	include_once('RouteController.php');
	new RouteController;
	// require_once 'default_page.php';
}else
{
	require_once 'install.php';
}
?>