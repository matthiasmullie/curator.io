<?php

/**
 * This will allow you to delete an item from your collection
 *
 * @package		items
 * @subpackage	delete
 *
 * @author 		Matthias Mullie <matthias@mullie.eu>
 * @since		1.0
 */
class ItemsDelete extends CuratorBaseAction
{
	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		$this->validateUser(true);
		$this->item->delete();
		$this->redirect($this->url->buildUrl('index', null, null, array('report' => 'deleted', 'var' => $this->item->name))); // @todo: redirect to items overview of this collections
	}
}
