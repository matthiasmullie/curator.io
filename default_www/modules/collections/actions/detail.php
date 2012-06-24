<?php

/**
 * Show the detail for an collection
 *
 * @author Tijs Verkoyen <tijs@sumocoders.be>
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
		$id = CollectionsHelper::getIdBySlug($userUri, $collectionUri);
		$this->collection = Collection::get($id);

		// logged in user vs collection user id
		if($this->collection->user_id != $this->currentUser->id)
		{
			$this->redirect($this->url->buildUrl('index', 'error') . '?code=404&message=CollectionNotFoundError');
		}

	}

	/**
	 * Parse template info.
	 */
	private function parse()
	{
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
