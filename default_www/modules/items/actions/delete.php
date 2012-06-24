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
		$this->validateCollection();
		$this->validateItem();
		$this->item->delete();
		$this->redirect($this->url->buildUrl('index', 'collections', null, array('report' => 'deleted', 'var' => $this->item->name)));
	}
}
