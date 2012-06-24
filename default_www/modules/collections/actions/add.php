<?php

/**
 * Create a new collection.
 *
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class CollectionsAdd extends SiteBaseAction
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		// user must be logged in
		if($this->currentUser === false) $this->redirect($this->url->buildUrl('forbidden', 'users') . '?redirect=' . urlencode('/' . $this->url->getQueryString()));

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

			// no errors?
			if($this->frm->isCorrect())
			{
				$item = new Collection();

				// set properties
				$item->name = $this->frm->getField('name')->getValue();
				$item->description = $this->frm->getField('description')->getValue();
				$item->user_id = $this->currentUser->id;

				// save
				$item->save();

				// redirect
				$collection = $item->toArray();
				$this->redirect($collection['full_uri'] . '?report=saved&var=' . $item->name);
			}

			// show general error
			else $this->tpl->assign('form' . SpoonFilter::toCamelCase($this->frm->getName()) . 'HasError', true);
		}
	}
}
