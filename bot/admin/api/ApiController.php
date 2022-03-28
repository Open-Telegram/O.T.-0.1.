<?php
/**
 * Created by VlAD for Cigitus company 24.10.2020
 */
class ApiController
{	
	public $inserts = 
	[
		'leftbar' => 'leftbar',
		'navbar' => 'navbar'
	];
	private $styles = [];
	public $content = '';
	protected $conf;
	protected $post = [];
	protected $get = [];
	private $data = [];
	private $display_setting_style = '';
	private $display_setting_template = '';
	public $telegram;

	public function __construct()
	{
		$this->styles = include_once($GLOBALS['PTH'].'system/styles.php');

		if(!empty($_SESSION['display_setting_style']))
		{
			$this->display_setting_style = $_SESSION['display_setting_style'];
		}else
		{
			$this->display_setting_style = 'liteStyle';
		}

		if(!empty($_SESSION['display_setting_template']))
		{
			$this->display_setting_template = $_SESSION['display_setting_template'];
		}else
		{
			$this->display_setting_template = 'default';
		}
		$this->styles = $this->styles[$this->display_setting_style];
		$this->conf = include_once($GLOBALS['PTH'].'system/config.php');
		$this->post = include_once($GLOBALS['PTH'].'system/post.php');
		$this->get = include_once($GLOBALS['PTH'].'system/get.php');
		if(!empty($_SESSION['user_name']))
		{
			$this->data['ThisUserName'] = $_SESSION['user_name'];
			$this->data['ThisUserStyleName'] = $this->display_setting_style;
			$this->data['ThisUserTemplateName'] = $this->display_setting_template;
		}else
		{
			$this->data['ThisUserRole'] = 999;
			$this->data['ThisUserName'] = 'Имя';
			$this->data['ThisUserId'] = 999;
			$this->data['ThisUserRoleName'] = 'Роль';
			$this->data['ThisUserStyleName'] = 'liteStyle';
			$this->data['ThisUserTemplateName'] = 'default';
		}
		if(isset($this->get['route']))
		{
			$this->data['route'] = $this->get['route'];
		}else
		{
			$this->data['route'] = '';
		}
		/**
		*------- подключение телеграм бота ------
		*/

		// include_once $GLOBALS['PTH'].'system/modules/telegram/telegram_c_mini.php';
		// $this->telegram = new telegrammSenderMini('');
	}

	public function getUserAvatar($userId,$userName)
	{
		$imgName = $userId . '.png';
		$jpgUrl = $GLOBALS['URL_IMAGES'] . 'avatars/' . $imgName;
		$jpgPAth = $GLOBALS['PATH_IMAGES'] . 'avatars/' . $imgName;
		$im = imagecreatetruecolor (100, 100) or die ("Ошибка при создании изображения");
		$randColor = imagecolorallocate($im, rand(160,200), rand(100,200), rand(100,200));
		imagefill($im, 0, 0, $randColor);
	    $font = 'Roboto';
		$textcolor = imagecolorallocate($im, 0, 0, 0);
		imagettftext($im, 50, 0, 27, 70, $textcolor, $font, mb_substr($userName, 0, 1));
	    ImagePng ($im,$jpgPAth);
		return '<img height="65" src="'.$jpgUrl.'?nocache='.rand(0,10000).'_'.rand(0,10000).'">';
	}


	function render($renderFileName = '')
	{
		if(empty($renderFileName))
		{
			die('Нет названия вида');
		}
		if (file_exists($GLOBALS['PTH'].'api/view/'.$this->display_setting_template.'/'. $renderFileName .'.php')) {

			$layout = $this->get_include_contents($GLOBALS['PTH'].'api/layout/'.$this->conf['layout'].'.php');
			
			if(!empty($this->styles))
			{
				$strStyles = '';
				foreach ($this->styles as $key => $style) {
					if(is_dir($_SERVER['DOCUMENT_ROOT'] . '/bot/admin/src/stylesheet/'.$this->display_setting_template.'/'. $this->display_setting_style))
					{
						$strStyles .= '<link href="/bot/admin/src/stylesheet/'.$this->display_setting_template.'/'. $this->display_setting_style .'/'.$style.'" rel="stylesheet">' . PHP_EOL;
					}else
					{
						$strStyles .= '<link href="/bot/admin/src/stylesheet/default/liteStyle/'.$style.'" rel="stylesheet">' . PHP_EOL;
					}
				}
				$layout = str_replace('[insert]styles[/insert]', $strStyles, $layout);
			}

			$content =  $this->get_include_contents($GLOBALS['PTH'].'api/view/'.$this->display_setting_template.'/'. $renderFileName .'.php');
			$layout = str_replace('[insert]content[/insert]', $content, $layout);
			if(!empty($this->inserts))
			{
				//$this->debug($this->inserts);
				foreach ($this->inserts as $key => $insert) {
					$insertContent = $this->get_include_contents($GLOBALS['PTH'].'api/layout/static/'.$insert.'.php');
					$layout = str_replace('[insert]'.$key.'[/insert]', $insertContent, $layout);
				}
			}

			//$layout = preg_replace('/[insert][\s\S]+?[/insert]/', '', $layout);
			$layout = $this->delete_all_between('[insert]','[/insert]',$layout);
			echo $layout;
			}else{die('Вид '.$renderFileName.'.php  не найден');}
	}

	public function p($data,$name='')//p - peremennaya
	{
		if(is_array($data) or !empty($name))
		{
			$this->data[$name] = $data;
		}else
		{
			$this->data[$data] = $data;
		}
	}

	public function getStaticHtml($renderFileName = '')
	{
		if(empty($renderFileName))
		{
			die('Нет названия элемента');
		}
		if (file_exists($GLOBALS['PTH'].'api/layout/static/'. $renderFileName .'.php')) {
				return $this->get_include_contents($GLOBALS['PTH'].'api/layout/static/'. $renderFileName .'.php');
			}else{die('Элемент '.$renderFileName.'.php  не найден');}
	}

	function get_include_contents($filename) {

		if(!empty($this->data))
		{
			foreach ($this->data as $key => $value) {
				$$key = $value;
			}
		}
	    if (is_file($filename)) {
	        ob_start();
	        include $filename;
	        return ob_get_clean();
	    }
	    return false;
	}

	public function setInsert($fileName,$insertName)
	{
		if(!empty($fileName) and !empty($insertName))
		{
			$this->inserts[$insertName] = $fileName;
		}
	}

	public function redirect($value,$data='')
	{
		header('Location: https://'.$_SERVER['HTTP_HOST'].'/bot/admin?route='.$value, true, 301);
	}

	public function addScript($script = '')
	{
		if(!empty($script))
		{
			$this->scripts += $script;
		}
	}

	public function loadModel($modelName)
	{
		if (file_exists($GLOBALS['PTH'].'api/model/'.$modelName.'.php')) {
				include_once $GLOBALS['PTH'].'api/model/'.$modelName.'.php';
				$modelName = explode('/', $modelName);
				$className = end($modelName).'Model';
				$controllerName = new $className;
				return $controllerName;
			}else{die('Файл '.$modelName.'.php контроллера /bot/admin/api/model/'.$modelName.' не найден');}
	}

	public function loadGSCP($file,$class)
	{
		if (file_exists($GLOBALS['PTH'].'system/modules/GSCP/'.$file.'.php')) {
				include_once $GLOBALS['PTH'].'system/modules/GSCP/'.$file.'.php';
				$className = new $class;
				return $className;
			}else{die('Файл /bot/admin/system/modules/GSCP/'.$file.'.php не найден');}
	}

	public function delete_all_between($beginning, $end, $string) {
	  $beginningPos = strpos($string, $beginning);
	  $endPos = strpos($string, $end);
	  if ($beginningPos === false || $endPos === false) {
	    return $string;
	  }

	  $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

	  return $this->delete_all_between($beginning, $end, str_replace($textToDelete, '', $string)); // recursion to ensure all occurrences are replaced
	}



	protected function debug($p)
	{	

		if(is_string($p))
		{
			if(isset($this->data[$p]))
			{
				echo '<pre>';
				print_r($this->data[$p]);
				echo '</pre>';
			}else
			{
				echo '<pre>';
				echo 'Такой переменной нет в реестре';
				echo '</pre>';
			}
		}else
		{
			echo '<pre>';
			print_r($p);
			echo '</pre>';
		}
	}

	function getRoleName($role)
	{
		switch ($role) {
			case '0':
				return 'Project manager';
				break;
			case '1':
				return 'Lead programmer';
				break;
			case '2':
				return 'Lead frontend developer';
				break;
			case '3':
				return 'Programmer';
				break;
			case '4':
				return 'Frontend developer';
				break;
			case '5':
				return 'Сustomer';
				break;
			
			default:
				return '';
				break;
		}
	}
}
?>