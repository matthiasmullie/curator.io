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

		// delete from Facebook
		if($this->item->facebook_id != '') $this->currentUser->deleteItemFromFacebook($this->item);

		$this->item->delete();
		$this->redirect($this->url->buildUrl('detail', 'collections') . '/' . $this->user->uri . '/' . $this->collection->uri . '?report=deleted&var=' . $this->item->name);
	}
}
