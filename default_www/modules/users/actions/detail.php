<?php

/**
 * Show the detail for an user
 *
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class UsersDetail extends SiteBaseAction
{
	/**
	 * @var User
	 */
	private $user;

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
		$this->user = User::getByUri($this->url->getParameter(1));
		if(!$this->user)
		{
			$this->redirect($this->url->buildUrl('index', 'error') . '?code=404&message=UserNotFoundError');
		}
	}

	/**
	 * Parse template info.
	 */
	private function parse()
	{
		$this->tpl->assign('title', $this->user->name);
		$this->tpl->assign('pageTitle', $this->user->name);
		$this->tpl->assign('user', $this->user->toArray());
		$this->tpl->assign('numCollections', $this->user->getCollectionCount());
		$this->tpl->assign('numItems', $this->user->getItemCount());
		$this->tpl->assign('numLikes', $this->user->getLikeCount());

		// get all collections
		$collections = $this->user->getCollections();
		if(!empty($collections))
		{
			foreach($collections as &$item) $item = $item->toArray();
			$this->tpl->assign('items', $collections);
		}

		// show some extra functionality
		if(Authentication::getLoggedInUser())
		{
			if(Authentication::getLoggedInUser()->id == $this->user->id)
			{
				$this->tpl->assign('isCurrentUser', true);
			}
		}

		$this->parseReports();
	}
}
