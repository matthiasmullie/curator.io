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
				$this->collections = new Collection();

				// set properties
				$this->collections->name = $this->frm->getField('name')->getValue();
				$this->collections->description = $this->frm->getField('description')->getValue();
				$this->collections->category_id = $this->frm->getField('category')->getValue();;
				$this->collections->user_id = $this->currentUser->id;

				// save
				$this->collections->save();

				// redirect
				$collection = $this->collections->toArray();
				$this->redirect($collection['full_uri'] . '?report=saved&var=' . $this->collections->name); // @todo: full_uri zou een magische property moeten zijn ipv deze vuiligheid
			}

			// show general error
			else $this->tpl->assign('form' . SpoonFilter::toCamelCase($this->frm->getName()) . 'HasError', true);
		}
	}
}
