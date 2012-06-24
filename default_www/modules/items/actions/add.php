<?php

/**
 * This will allow you to add an item to your collection
 *
 * @package		items
 * @subpackage	add
 *
 * @author 		Matthias Mullie <matthias@mullie.eu>
 * @since		1.0
 */
class ItemsAdd extends SiteBaseAction
{
	/**
	 * Form object
	 *
	 * @var SpoonForm
	 */
	protected $frm;

	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		// user must be logged in
		if($this->currentUser === false) $this->redirect($this->url->buildUrl('forbidden', 'users') . '?redirect=' . urlencode('/' . $this->url->getQueryString()));

		$this->loadForm();
		$this->validateForm();
		$this->processForm();

		// parse
		$this->parse();

		// display the page
		$this->display();
	}

	/**
	 * Load upload-CSV form
	 *
	 * @return void
	 */
	protected function loadForm()
	{
		$this->frm = new SpoonForm('add');

		$this->frm->addText('name');
		$this->frm->addTextArea('description');
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
		$collection = Collection::getByUri($this->url->getParameter(2));
		if(!$collection) $this->redirect($this->url->buildUrl('index', 'error'), 301);

		// build & save item
		$item = new Item();
		$item->collection_id = $collection->id;
		foreach($this->frm->getValues(array('form', '_utf8')) as $key => $value) $item->$key = $value;
		$item->image = $this->frm->getField('image');
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
		$this->frm->getField('name')->isFilled('Please etner enter a name.');
	}

	/**
	 * Parse the page
	 *
	 * @return void
	 */
	private function parse()
	{
		$this->frm->parse($this->tpl);
	}
}
