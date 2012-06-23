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
		$this->parseReports();
	}
}
