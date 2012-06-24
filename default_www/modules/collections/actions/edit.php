<?php

/**
 * Edit an existing collection.
 *
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class CollectionsEdit extends CuratorBaseAction
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		$this->validateUser(true);
		$this->validateCollection();
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
		$this->frm = new SiteForm('edit');

		$this->frm->addText('name', $this->collection->name)->setAttributes(array('required' => null));
		$this->frm->addTextarea('description', $this->collection->description);
	}

	/**
	 * Parse the page
	 */
	private function parse()
	{
		$this->tpl->assign('title', 'Edit collection');
		$this->tpl->assign('collection', $this->collection->toArray());
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

			// no errors?
			if($this->frm->isCorrect())
			{
				// set properties
				$this->collection->name = $this->frm->getField('name')->getValue();
				$this->collection->description = $this->frm->getField('description')->getValue();

				// save
				$this->collection->save();

				// redirect
				$collection = $this->collection->toArray();
				$this->redirect($collection['full_uri'] . '?report=saved&var=' . $this->collection->name); // @todo: dit cast naar array voor die full_uri is vuil!
			}

			// show general error
			else $this->tpl->assign('form' . SpoonFilter::toCamelCase($this->frm->getName()) . 'HasError', true);
		}
	}
}
