<?php

/**
 * ExampleIndex
 *
 * @package		pages
 * @subpackage	about
 *
 * @author 		Tijs Verkoyen <tijs@sumocoders.be>
 * @since		1.0
 */
class PagesAbout extends SiteBaseAction
{
	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		// display the page
		$this->display();
	}
}
