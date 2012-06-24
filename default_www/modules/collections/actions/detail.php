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
	 * @var User
	 */
	private $collectionOwner;

	/**
	 * Execute the action
	 */
	public function execute()
	{
		/// exists
		if(!CollectionsHelper::existsBySlug($this->url->getParameter(1)))
		{
			$this->redirect($this->url->buildUrl('index', 'error') . '?code=404&message=CollectionNotFoundError');
		}

		// fetch data
		$id = CollectionsHelper::getIdBySlug($this->url->getParameter(1));
		$this->collection = Collection::get($id);
		$this->collectionOwner = User::get($this->collection->user_id);

		$this->parse();
		$this->display();
	}

	/**
	 * Parse template info.
	 */
	private function parse()
	{
		$this->tpl->assign('collection', $this->collection->toArray());
		$this->tpl->assign('collectionOwner', $this->collectionOwner->toArray());

		if(Authentication::getLoggedInUser())
		{
			if(Authentication::getLoggedInUser()->id == $this->collection->user_id)
			{
				$this->tpl->assign('isCollectionOwner', true);
			}
		}
	}
}
