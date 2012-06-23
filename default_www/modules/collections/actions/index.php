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
		// @todo
	}
}
