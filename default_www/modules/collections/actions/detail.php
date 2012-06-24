<?php

/**
 * Show the detail for an collection
 *
 * @author Tijs Verkoyen <tijs@sumocoders.be>
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class CollectionsDetail extends CuratorBaseAction
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		$this->validateUser();
		$this->validateCollection();
		$this->parse();
		$this->display();
	}

	/**
	 * Parse template info.
	 */
	private function parse()
	{
		$this->tpl->assign('title', $this->collection->name);
		$this->tpl->assign('pageTitle', $this->collection->name);
		$this->tpl->assign('collection', $this->collection->toArray());
		$this->tpl->assign('isCollectionOwner', Authentication::getLoggedInUser() && Authentication::getLoggedInUser()->id == $this->collection->user_id);
		$this->parseReports(); // @todo: kijken of dit nog op andere actions moet terugkomen
	}
}
