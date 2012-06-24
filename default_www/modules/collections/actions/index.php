<?php

/**
 * Homepage
 *
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class CollectionsIndex extends SiteBaseAction
{
	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		// @later detect if we are accessing /collections directly. If so => redirect to home page to prevent duplicate content

		// parse
		$this->parse();

		// display the page
		$this->display();
	}

	/**
	 * Parse the page
	 *
	 * @return void
	 */
	private function parse()
	{
		$this->parseReports();

		$sort = SpoonFilter::getValue($this->url->getParameter(1), array('popular', 'latest', 'categories'), 'popular');

		$this->tpl->assign('sort' . SpoonFilter::toCamelCase($sort), true);

		switch($sort)
		{
			case 'categories':
				$this->parseCategories();
				break;
			case 'latest':
				$this->parseLatest();
				break;
			case 'popular':
			default:
				$this->parsePopular();
				break;
		}
	}

	/**
	 * Parse the collections based on category?
	 */
	private function parseCategories()
	{
		throw new Exception('Implement me');
	}

	/**
	 * Parse the collection based on the like count
	 */
	private function parseLatest()
	{
		// get all collections sorted by likes
		$collections = CollectionsHelper::getOrderByCreatedOn();

		if(!empty($collections))
		{
			foreach($collections as &$collection) $collection = $collection->toArray();
			$this->tpl->assign('collections', $collections);
		}
	}

	/**
	 * Parse the collection based on the like count
	 */
	private function parsePopular()
	{
		// get all collections sorted by likes
		$collections = CollectionsHelper::getOrderByLike();

		if(!empty($collections))
		{
			foreach($collections as &$collection) $collection = $collection->toArray();
			$this->tpl->assign('collections', $collections);
		}
	}
}
