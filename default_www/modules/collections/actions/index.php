<?php

/**
 * Homepage
 *
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class CollectionsIndex extends SiteBaseAction
{
	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		// @later detect if we are accessing /collections directly. If so => redirect to home page to prevent duplicate content

		// parse
		$this->parse();

		// display the page
		$this->display();
	}

	/**
	 * Parse the page
	 *
	 * @return void
	 */
	private function parse()
	{
		require_once PATH_WWW .'/modules/items/model/model.php';

		if($this->currentUser !== false) $this->currentUser->publishItemToFacebook(Item::getById(1));


		$this->parseReports();
	}
}
