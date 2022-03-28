<?php
namespace bot\api\controller;
include($_SERVER['DOCUMENT_ROOT'] . '/bot/api/controller.php');
use bot\api\controller;
/**
 * 
 */
class inlineQuery extends Controller
{
	public $data;
	private $conf;
	function __construct($data,$conf)
	{
		$this->data = $data;
		$this->conf = $conf;
	}
}
?>