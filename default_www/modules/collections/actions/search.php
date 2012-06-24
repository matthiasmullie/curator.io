<?php

/**
 * Search page
 *
 * @author Tijs Verkoyen <tijs@sumocoders.be>
 */
class CollectionsSearch extends SiteBaseAction
{
	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
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
		$term = SpoonFilter::getGetValue('q', null, '');

		$collections = CollectionsHelper::search($term);
		if(!empty($collections))
		{
			foreach($collections as &$item) $item = $item->toArray();
			$this->tpl->assign('collections', $collections);
		}

		$items = Item::search($term);
		if(!empty($items))
		{
			$this->tpl->assign('items', $items);
		}

		if(empty($collections) && empty($items)) $this->tpl->assign('noItems');
		$this->tpl->assign('term', $term);
	}
}
