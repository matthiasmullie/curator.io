<?php

/**
 * UsersForbidden
 *
 * @package		users
 * @subpackage	forbidden
 *
 * @author 		Tijs Verkoyen <tijs@sumocoders.be>
 * @since		1.0
 */
class UsersForbidden extends SiteBaseAction
{
	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		if($this->currentUser !== false) $this->redirect('/' . $this->url->getLanguage());

		// display the page
		$this->display();
	}
}
