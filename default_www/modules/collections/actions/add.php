<?php

/**
 * Create a new collection.
 *
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class CollectionsAdd extends CuratorBaseAction
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		$this->validateUser(true);
		$this->loadForm();
		$this->validateForm();
		$this->parse();
		$this->display();
	}

	/**
	 * Create the add form.
	 */
	private function loadForm()
	{
		$this->frm = new SiteForm('add');

		$this->frm->addText('name')->setAttributes(array('required' => null));
		$this->frm->addTextarea('description');
		$this->frm->addDropdown('category', CollectionsHelper::getCategoriesForDropdown())->setAttributes(array('required' => null));

		$this->frm->getField('category')->setDefaultElement('');
	}

	/**
	 * Parse the page
	 */
	private function parse()
	{
		$this->tpl->assign('title', 'Add collection');
		$this->frm->parse($this->tpl);
	}

	/**
	 * Validate the form
	 */
	private function validateForm()
	{
		// submitted?
		if($this->frm->isSubmitted())
		{
			// validate required fields
			$this->frm->getField('name')->isFilled(SiteLocale::err('FieldIsRequired'));
			$this->frm->getField('category')->isFilled(SiteLocale::err('FieldIsRequired'));

			// no errors?
			if($this->frm->isCorrect())
			{
				$this->collection = new Collection();

				// set properties
				$this->collection->name = $this->frm->getField('name')->getValue();
				$this->collection->description = $this->frm->getField('description')->getValue();
				$this->collection->category_id = $this->frm->getField('category')->getValue();;
				$this->collection->user_id = $this->currentUser->id;

				// save
				$this->collection->save();

				// redirect
				$this->redirect($this->url->buildUrl('detail', 'collections') . '/' . $this->user->uri . '/' . $this->collection->uri . '?report=saved&var=' . $this->collection->name);
			}

			// show general error
			else $this->tpl->assign('form' . SpoonFilter::toCamelCase($this->frm->getName()) . 'HasError', true);
		}
	}
}
