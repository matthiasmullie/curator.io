<?php

/**
 * This will import your collection in CSV into our sweet curator.io
 *
 * @package		items
 * @subpackage	detail
 *
 * @author 		Tijs Verkoyen <tijs@sumocoders.be>
 * @since		1.0
 */
class ItemsDetail extends SiteBaseAction
{
	/**
	 * The item
	 *
	 * @var Item
	 */
	private $item;

	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		// @todo	validation
		$this->item = Item::getByUri($this->url->getParameter(1));

		// build open graph data
		$openGraph = array();
		$openGraph[] = array('key' => 'url', 'value' => SITE_URL . $this->url->buildUrl('detail', 'items') . '/' . $this->item->uri);
		$openGraph[] = array('key' => 'title', 'value' => $this->item->name);
		$openGraph[] = array('key' => 'description', 'value' => $this->item->description);
		$openGraph[] = array('key' => 'image', 'value' => SITE_URL . '/files/items/130x110/' . $this->item->image);


		if(!empty($openGraph)) $this->tpl->assign('opengraph', $openGraph);

// 		SPoon::dump($this->item);

		$this->display();
	}
}
