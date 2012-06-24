<?php

/**
 * Show the detail for an collection
 *
 * @author Tijs Verkoyen <tijs@sumocoders.be>
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class CollectionsDetail extends SiteBaseAction
{
	/**
	 * @var Collection
	 */
	private $collection;

	/**
	 * Execute the action
	 */
	public function execute()
	{
		$this->loadData();
		$this->parse();
		$this->display();
	}

	/**
	 * Load/validate data.
	 */
	private function loadData()
	{
		$userUri = $this->url->getParameter(1);
		$collectionUri = $this->url->getParameter(2);

		// exists
		if(!CollectionsHelper::existsBySlug($userUri, $collectionUri))
		{
			$this->redirect($this->url->buildUrl('index', 'error') . '?code=404&message=CollectionNotFoundError');
		}

		// fetch data
		$id = CollectionsHelper::getIdBySlug($this->url->getParameter(1), $this->url->getParameter(2));

		$this->collection = Collection::get($id);
	}

	/**
	 * Parse template info.
	 */
	private function parse()
	{
		$this->tpl->assign('title', $this->collection->name);
		$this->tpl->assign('pageTitle', $this->collection->name);
		$this->tpl->assign('collection', $this->collection->toArray());

		if(Authentication::getLoggedInUser())
		{
			if(Authentication::getLoggedInUser()->id == $this->collection->user_id)
			{
				$this->tpl->assign('isCollectionOwner', true);
			}
		}

		$this->parseReports();
	}
}
