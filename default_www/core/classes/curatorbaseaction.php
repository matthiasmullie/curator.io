<?php

/**
 * Provide some common functionality accross the platform
 *
 * @package		core
 * @subpackage	baseaction
 *
 * @author 		Matthias Mullie <matthias@mullie.eu>
 * @since		1.0
 */
class CuratorBaseAction extends SiteBaseAction
{
	/**
	 * The user object based on the uri params
	 *
	 * @var User
	 */
	protected $user;

	/**
	 * The collection object based on the uri params
	 *
	 * @var Collection
	 */
	protected $collection;

	/**
	 * The item object based on the uri params
	 *
	 * @var Item
	 */
	protected $item;

	public function __get($property)
	{
		switch($property)
		{
			case 'user':
				require_once PATH_WWW . '/modules/users/model/model.php';
				if(!isset($this->user)) $this->user = User::getByUri($this->url->getParameter(1));
				return $this->user;
				break;
			case 'collection':
				require_once PATH_WWW . '/modules/collections/model/model.php';
				if(!isset($this->collection)) $this->collection = Collection::getByUri($this->url->getParameter(2), $this->url->getParameter(1));
				return $this->collection;
			case 'item':
				require_once PATH_WWW . '/modules/items/model/model.php';
				if(!isset($this->item)) $this->item = Item::getByUri($this->url->getParameter(3), $this->url->getParameter(2), $this->url->getParameter(1));
				return $this->item;
				break;
		}
	}

	/**
	 * Validate user: must be logged in & match the url slug
	 *
	 * @param bool[optional] $loggedin Also validate that the correct user is logged in and matches uri slug
	 */
	protected function validateUser($loggedin = false)
	{
		if($loggedin)
		{
			// user must be logged in & slug in url must be this user
			if($this->currentUser === false) $this->redirect($this->url->buildUrl('forbidden', 'users') . '?redirect=' . urlencode('/' . $this->url->getQueryString()));
			elseif($this->currentUser->uri !== $this->__get('user')->uri) $this->redirect($this->url->buildUrl('index', 'error') . '?code=404&message=InvalidUser');
		}

		if(!$this->__get('user')) $this->redirect($this->url->buildUrl('index', 'error') . '?code=404&message=UserNotFoundError', 301);
	}

	/**
	 * Validate collection: slug must point to valid item
	 */
	protected function validateCollection()
	{
		if(!$this->__get('collection')) $this->redirect($this->url->buildUrl('index', 'error') . '?code=404&message=CollectionNotFoundError', 301);
	}

	/**
	 * Validate item: slug must point to valid item
	 */
	protected function validateItem()
	{
		if(!$this->__get('item')) $this->redirect($this->url->buildUrl('index', 'error') . '?code=404&message=ItemNotFoundError', 301);
	}
}
