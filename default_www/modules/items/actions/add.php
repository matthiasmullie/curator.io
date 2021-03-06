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
		$this->frm = new SiteForm('add');

		$this->frm->addText('name');
		$this->frm->addTextArea('description');
		$this->frm->addImage('image');
		$this->frm->addCheckbox('publish_to_facebook');
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
		foreach($this->frm->getValues(array('form', '_utf8', 'publish_to_facebook')) as $key => $value) $this->item->$key = $value;
		if($this->frm->getField('image')->isFilled()) $this->item->image = $this->frm->getField('image');

		$names = SpoonFilter::getPostValue('names', null, null, 'array');
		$values = SpoonFilter::getPostValue('values', null, null, 'array');
		if($names && $values)
		{
			foreach(array_combine($names, $values) as $names => $value)
			{
				$this->item->{$names} = $value;
			}
		}
		$this->item->save();

		if($this->frm->getField('publish_to_facebook')->getChecked())
		{
			$this->currentUser->publishItemToFacebook($this->item);
		}

		// redirect to brand new item
		$this->redirect($this->url->buildUrl('detail') . '/' . $this->user->uri . '/' . $this->collection->uri . '/' . $this->item->uri . '?report=saved&var=' . $this->item->name);
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

		// assign custom fields already used in this collection
		$this->tpl->assign('custom', (array) Item::getCustomFields($this->collection->id));
	}
}
