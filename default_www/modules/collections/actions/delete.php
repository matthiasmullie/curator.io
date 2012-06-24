<?php

/**
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class CollectionsDelete extends SiteBaseAction
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
		// user must be logged in
		if($this->currentUser === false) $this->redirect($this->url->buildUrl('forbidden', 'users') . '?redirect=' . urlencode('/' . $this->url->getQueryString()));

		$this->loadData();

		$this->collection->delete();

		$this->redirect($this->url->buildUrl('index', null, null, array('report' => 'deleted', 'var' => $this->collection->name)));
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
}
