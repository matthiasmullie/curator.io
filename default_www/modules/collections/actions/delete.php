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
		// authentication
		if(!Authentication::getLoggedInUser()) $this->redirect($this->url->buildUrl('forbidden', 'users'));

		// exists
		if(!CollectionsHelper::existsBySlug($this->url->getParameter(1)))
		{
			$this->redirect($this->url->buildUrl('index', 'error') . '?code=404&message=CollectionNotFoundError');
		}

		// fetch data
		$id = CollectionsHelper::getIdBySlug($this->url->getParameter(1));
		$this->collection = Collection::get($id);

		// logged in user vs collection user id
		if($this->collection->user_id != Authentication::getLoggedInUser()->id)
		{
			$this->redirect($this->url->buildUrl('index', 'error') . '?code=404&message=CollectionNotFoundError');
		}

		$this->collection->delete();

		$this->redirect($this->url->buildUrl('index', null, null, array('report' => 'deleted', 'var' => $this->collection->name)));
	}
}
