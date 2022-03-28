<?php
include_once $GLOBALS['PTH'].'api/ApiModel.php';
class modulesModel extends ApiModel
{
	public function get_all_functions()
	{

		$functions = [];
		$sql = "SELECT * FROM `modules`";
		$modulesSql = mysqli_query($this->db,$sql);
		$modules = [];
		if($modulesSql)
		{
			while ($modules[] = mysqli_fetch_assoc($modulesSql)) {}
			array_pop($modules);
		}
		if(!empty($modules))
		{
			foreach ($modules as $key => $module) 
			{

				$module_path = "bot\api\controller\modules\\".$module['name'].'Controller';
				@include($_SERVER['DOCUMENT_ROOT'] ."/bot/api/controller/modules/".$module['name'].'.php');
				$moduleArr = @new $module_path();
				$moduleInfo = @$moduleArr->info();
				if($moduleInfo and !empty($moduleInfo))
				{
					foreach ($moduleInfo['functions'] as $key => $function) {
						
						$functions[$function] = $moduleInfo[$function];

						$functions[$function]['module_name'] = $module['name'];
						$functions[$function]['creator_name'] = $moduleInfo['creator_name'];
					}
				}
			}
		}
		return $functions;

	}

	public function deleteModule($name)
	{
		//Получаем модуль по имени
		$sql = "SELECT * FROM `modules` WHERE name = '$name'";
		$moduleSql = mysqli_query($this->db,$sql);
		$module = [];
		if($moduleSql)
		{
			$module = mysqli_fetch_assoc($moduleSql);
		}
		if(isset($module['module_id']))
		{
			//Удаляем этот модуль
			$module_id = $module['module_id'];
			$sql = "DELETE FROM `modules` WHERE module_id = $module_id";
			$moduleSql = mysqli_query($this->db,$sql);

			//Получаем все его функции
			$functions = [];
			$sql = "SELECT * FROM `functions` WHERE module_id = $module_id";
			$functionsSql = mysqli_query($this->db,$sql);
			if($functionsSql)
			{
				while ($functions[] = mysqli_fetch_assoc($functionsSql)) {}
				array_pop($functions);
			}

			if(!empty($functions))
			{
				foreach ($functions as $key => $function) 
				{
					//удаляем все процедуры связанные с этими функциями
					$sql = "DELETE FROM `function_procedure` WHERE function_id = ".$function['function_id'];
					$moduleSql = mysqli_query($this->db,$sql);
				}
			}

			//Удаляем все функции
			$sql = "DELETE FROM `functions` WHERE module_id = $module_id";
			$moduleSql = mysqli_query($this->db,$sql);
		}
	}

	public function get_functions_for_module($module_id)
	{

		$functions = [];
		$modules = [];
		$sql = "SELECT * FROM `modules` WHERE module_id = $module_id";
		$modulesSql = mysqli_query($this->db,$sql);
		if($modulesSql)
		{
			while ($modules[] = mysqli_fetch_assoc($modulesSql)) {}
			array_pop($modules);
		}
		if(!empty($modules))
		{
			foreach ($modules as $key => $module) 
			{

				$module_path = "bot\api\controller\modules\\".$module['name'].'Controller';
				@include($_SERVER['DOCUMENT_ROOT'] ."/bot/api/controller/modules/".$module['name'].'.php');
				$moduleArr = @new $module_path();
				$moduleInfo = @$moduleArr->info();
				if($moduleInfo and !empty($moduleInfo))
				{
					foreach ($moduleInfo['functions'] as $key => $function) {
						
						$functions[$function] = $moduleInfo[$function];

						$functions[$function]['module_name'] = $module['name'];
						$functions[$function]['creator_name'] = $moduleInfo['creator_name'];
					}
				}
			}
		}
		return $functions;

	}

	public function setNewModule($module_id,$name)
	{
		$sql = "SELECT * FROM `modules` WHERE module_id = $module_id";
		$modules = [];
		$modulesSql = mysqli_query($this->db,$sql);
		if($modulesSql)
		{
			while ($modules[] = mysqli_fetch_assoc($modulesSql)) {}
			array_pop($modules);
		}
		if(!empty($modules))
		{
			$sql = "DELETE FROM `modules` WHERE module_id = $module_id";
			mysqli_query($this->db,$sql);
		}
		$sql = "INSERT INTO `modules` (`module_id`, `name`, `version`) VALUES ('$module_id', '$name', '1')";
		mysqli_query($this->db,$sql);
		$functions = $this->get_functions_for_module($module_id);
		if(!empty($functions))
		{
			foreach ($functions as $key => $function) 
			{
				$sql = "SELECT * FROM `functions` WHERE module_id = $module_id and function_name = '". $key ."'";
				$funcsql = mysqli_query($this->db,$sql);
				$f = [];
				while ($f[] = mysqli_fetch_assoc($funcsql)) {}
				array_pop($f);
				if(empty($f))
				{
					$sql = "INSERT INTO `functions` (`function_id`, `module_id`, `function_name`) VALUES (NULL, '$module_id', '$key')";
					mysqli_query($this->db,$sql);
				}
			}
		}
	}
}
?>