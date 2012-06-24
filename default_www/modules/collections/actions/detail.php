<?php

/**
 * Show the detail for an collection
 *
 * @author Tijs Verkoyen <tijs@sumocoders.be>
 */
class CollectionsDetail extends SiteBaseAction
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		// @todo	better validation
		$this->item = Collection::getByUri($this->url->getParameter(1));

		Spoon::dump($this->item);
	}
}
