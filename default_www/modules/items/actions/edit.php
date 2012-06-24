<?php

/**
 * This will allow you to edit an item to your collection
 *
 * @package		items
 * @subpackage	edot
 *
 * @author 		Matthias Mullie <matthias@mullie.eu>
 * @since		1.0
 */
class ItemsEdit extends SiteBaseAction
{
	/**
	 * Form object
	 *
	 * @var SpoonForm
	 */
	protected $frm;

	/**
	 * The item we'll be editing
	 *
	 * @var Item
	 */
	protected $item;

	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		// user must be logged in
		if($this->currentUser === false) $this->redirect($this->url->buildUrl('forbidden', 'users') . '?redirect=' . urlencode('/' . $this->url->getQueryString()));

		$this->loadData();
		$this->loadForm();
		$this->validateForm();
		$this->processForm();

		// parse
		$this->parse();

		// display the page
		$this->display();
	}

	/**
	 * Load the data
	 *
	 * @return void
	 */
	protected function loadData()
	{
		$userUri = $this->url->getParameter(1);
		$collectionUri = $this->url->getParameter(2);
		$uri = $this->url->getParameter(3);

		$this->item = Item::getByUri($uri, $collectionUri, $userUri);
		if(!$this->item) $this->redirect($this->url->buildUrl('index', 'error'), 301);
	}

	/**
	 * Load upload-CSV form
	 *
	 * @return void
	 */
	protected function loadForm()
	{
		$this->frm = new SpoonForm('edit');

		$this->frm->addText('name', $this->item->name);
		$this->frm->addTextArea('description', $this->item->description);
		$this->frm->addImage('image');
	}

	/**
	 * Process incoming data
	 *
	 * @return void
	 */
	protected function processForm()
	{
		if(!$this->frm->isSubmitted() || !$this->frm->isCorrect()) return;

		// fetch collection
		require_once PATH_WWW . '/modules/collections/model/model.php';
		$collection = Collection::getByUri($this->url->getParameter(1));
		if(!$collection) $this->redirect($this->url->buildUrl('index', 'error'), 301);

		// build & save item
		$item = $this->item;
		foreach($this->frm->getValues(array('form', '_utf8')) as $key => $value) $item->$key = $value;
		if($this->frm->getField('image')->isFilled()) $item->image = $this->frm->getField('image');
		$item->save();

		// @todo: custom fields = wait for design

		// redirect to brand new item
		$this->redirect($this->url->buildUrl('detail') . '/' . $this->currentUser->uri . '/' . $collection->uri . '/' . $item->uri);
	}

	/**
	 * Validate incoming data
	 *
	 * @return void
	 */
	protected function validateForm()
	{
		if(!$this->frm->isSubmitted()) return;

		// validate filled
		$this->frm->getField('name')->isFilled('Please enter enter a name.');
	}

	/**
	 * Parse the page
	 *
	 * @return void
	 */
	private function parse()
	{
		$this->frm->parse($this->tpl);
		$this->tpl->assign('image', $this->item->image);
	}
}
