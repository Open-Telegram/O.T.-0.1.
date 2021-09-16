<?php
/**
 * Created by VlAD for Cigitus company 24.10.2020
 */
include_once $GLOBALS['PTH'].'api/ApiController.php';
class buttonsController extends ApiController
{
	function index()
	{
		$modulesModel = $this->loadModel('modules/modules');
		$commModel = $this->loadModel('commands/commands');
		$buttonsModel = $this->loadModel('buttons/buttons');
		
		$this->p($modulesModel->get_all_functions(),'functions');
		$this->p($commModel->get_all_commands(),'commands');
		$this->p($buttonsModel->get_all_buttons(),'buttons');
		$this->render('buttons/buttons');
	}

	function save()
	{
		if(isset($this->post['buttons']))
		{
			$buttonModel = $this->loadModel('buttons/buttons');
			$buttonModel->clearAll();
			$buttons_ids = [];

			foreach ($this->post['buttons'] as $button_id => $button) 
			{
				if(!empty($button['button_name']))
				{
					$buttons_ids[] = "'$button_id'";
				}

			}
			$buttons_ids = implode(',', $buttons_ids);
			$buttonModel->deleteButtons($buttons_ids);
			foreach ($this->post['buttons'] as $button_id => $button) 
			{
				if(!empty($button['button_name']))
				{
					$buttonModel->save($button,$button_id);
				}

			}
		}
		$this->redirect('buttons/index');
	}
}
?>