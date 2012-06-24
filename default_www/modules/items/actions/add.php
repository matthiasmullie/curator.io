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
class ItemsAdd extends CuratorBaseAction
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
		$this->validateUser(true);
		$this->validateCollection();
		$this->loadForm();
		$this->validateForm();
		$this->processForm();
		$this->parse();
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

		// build & save item
		$this->item = new Item();
		$this->item->collection_id = $this->collection->id;
		foreach($this->frm->getValues(array('form', '_utf8')) as $key => $value) $this->item->$key = $value;
		if($this->frm->getField('image')->isFilled()) $this->item->image = $this->frm->getField('image');
		$this->item->save();

		// @todo: custom fields = wait for design

		// redirect to brand new item
		$this->redirect($this->url->buildUrl('detail') . '/' . $this->currentUser->uri . '/' . $this->collection->uri . '/' . $this->item->uri);
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
	}
}
