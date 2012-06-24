<?php

/**
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class CollectionsDelete extends SiteBaseAction
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		// user must be logged in
		if($this->currentUser === false) $this->redirect($this->url->buildUrl('forbidden', 'users') . '?redirect=' . urlencode('/' . $this->url->getQueryString()));

		// exists
		if(!CollectionsHelper::existsBySlug($this->url->getParameter(1)))
		{
			$this->redirect($this->url->buildUrl('index', 'error') . '?code=404&message=CollectionNotFoundError');
		}

		// fetch data
		$id = CollectionsHelper::getIdBySlug($this->url->getParameter(1));
		$this->collection = Collection::get($id);

		// logged in user vs collection user id
		if($this->collection->user_id != $this->currentUser->id)
		{
			$this->redirect($this->url->buildUrl('index', 'error') . '?code=404&message=CollectionNotFoundError');
		}

		$this->collection->delete();

		$this->redirect($this->url->buildUrl('index', null, null, array('report' => 'deleted', 'var' => $this->collection->name)));
	}
}
