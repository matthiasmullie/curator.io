<?php

/**
 * This will display an item in your collection
 *
 * @package		items
 * @subpackage	detail
 *
 * @author 		Tijs Verkoyen <tijs@sumocoders.be>
 * @since		1.0
 */
class ItemsDetail extends CuratorBaseAction
{
	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		$this->validateUser();
		$this->validateCollection();
		$this->validateItem();
		$this->parse();
		$this->display();
	}

	/**
	 * Parse
	 */
	private function parse()
	{
		$this->tpl->assign('title', $this->item->name);
		$this->tpl->assign('item', $this->item->toArray());
 		$this->tpl->assign('category', CollectionsHelper::getCategoryById($this->collection->category_id));
		$this->tpl->assign('isOwner', Authentication::getLoggedInUser() && Authentication::getLoggedInUser()->id == $this->collection->user_id);

		// build open graph data
		$openGraph = array();
		$openGraph[] = array('key' => 'url', 'value' => SITE_URL . $this->url->buildUrl('detail', 'items') . '/' . $this->item->uri);
		$openGraph[] = array('key' => 'title', 'value' => $this->item->name);
		$openGraph[] = array('key' => 'description', 'value' => $this->item->description);
		$openGraph[] = array('key' => 'image', 'value' => SITE_URL . '/files/items/130x110/' . $this->item->image);
		$openGraph[] = array('key' => 'type', 'value' => 'curatorio:item');
		if(!empty($openGraph)) $this->tpl->assign('opengraph', $openGraph);

		$this->parseReports();
	}
}
