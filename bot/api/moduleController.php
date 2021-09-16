<?php
namespace bot\api;

class moduleController
{

	function __construct()
	{
	}

	function debug($data)
	{
        ob_start();
        print_r($data);
        $out = ob_get_clean(); 
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/messageDEB.txt', $out, FILE_APPEND); 
	}
    public function getInlineKeyBoard($data)
    {
        $inlineKeyboard = array(
            "inline_keyboard" => $data,
        );
        return json_encode($inlineKeyboard);
    }
     

    public function getKeyBoard($data,$one_time_keyboard = true)
    {
        $keyboard = array(
            "keyboard" => $data,
            "one_time_keyboard" => $one_time_keyboard,
            "resize_keyboard" => true
        );
        return json_encode($keyboard);
    }
}
?>