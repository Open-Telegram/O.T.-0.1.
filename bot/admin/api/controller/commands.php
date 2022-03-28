<?php
/**
 * Created by VlAD for Cigitus company 24.10.2020
 */
include_once $GLOBALS['PTH'].'api/ApiController.php';
class commandsController extends ApiController
{
	function index()
	{
		$modulesModel = $this->loadModel('modules/modules');
		$commModel = $this->loadModel('commands/commands');
		$buttonsModel = $this->loadModel('buttons/buttons');
		
		$this->p($modulesModel->get_all_functions(),'functions');
		$this->p($commModel->get_all_commands(),'commands');
		$this->p($buttonsModel->get_all_buttons(),'buttons');
		$this->render('commands/commands');
	}

	function save()
	{
		if(isset($this->post['commands']))
		{
			$commModel = $this->loadModel('commands/commands');
			$commModel->clearAll();
			$commands_ids = [];
			foreach ($this->post['commands'] as $command_id => $command) 
			{
				if(!empty($command['command_text']))
				{
					$commands_ids[] = "'$command_id'";
				}

			}
			$commands_ids = implode(',', $commands_ids);
			$commModel->deleteCommands($commands_ids);
			foreach ($this->post['commands'] as $command_id => $command) 
			{
				if(!empty($command['command_text']))
				{
					$commModel->save($command,$command_id);
				}

			}
		}
		$this->redirect('commands/index');
	}
}
?>