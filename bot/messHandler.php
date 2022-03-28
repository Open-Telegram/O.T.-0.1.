<?php 
namespace bot;
include('bot/engine.php');
use bot\engine;


class messHandler extends engine
{
	public function __construct($mess)
	{
		parent::__construct($mess);
		switch ($this->data['Message']['type'])
		{
			case 'message':
				include('bot/api/controller/message.php');
				new api\controller\message($this->data,$this->conf);
				break;
			case 'inline_query':
				include('bot/api/controller/inlineQuery.php');
				new api\controller\inlineQuery($this->data,$this->conf);
				break;
			case 'inline_answer':
				break;
			case 'callback_query':
				include('bot/api/controller/callbackQuery.php');
				new api\controller\callbackQuery($this->data,$this->conf);
				break;
			case 'photo':
			//$this->addPhoto();
				break;
			default:
				break;
		}

	}


 
    private function getPhoto($data)
    {
        $file_id = $data[count($data) - 1]['file_id'];
        $file_path = $this->getPhotoPath($file_id);
        return $this->copyPhoto($file_path);
    }

    private function getPhotoPath($file_id) {
    	// получаем объект File
        $array = json_decode($this->requestToTelegram(['file_id' => $file_id], "getFile"), TRUE);
        //$this->debWrite($file_id);
        //$this->debWrite($array);
        // возвращаем file_path
        return  $array['result']['file_path'];
    }

    private function copyPhoto($file_path) {
        $file_from_tgrm = "https://api.telegram.org/file/bot".$this->conf['Telegram_api']."/".$file_path;
        // достаем расширение файла
        $ext =  end(explode(".", $file_path));
        $name_our_new_file = rand(1,1500) . time().".".$ext;
        if(copy($file_from_tgrm, "bot/photos/".$name_our_new_file))
        {
        	return $name_our_new_file;
        }else
        {
        	return 'Не удалось загрузить фото';
        }
    }
}
?>