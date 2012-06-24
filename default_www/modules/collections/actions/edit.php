<?php

/**
 * Edit an existing collection.
 *
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class CollectionsEdit extends SiteBaseAction
{
	/**
	 * @var Collection
	 */
	private $collection;

	/**
	 * Execute the action
	 */
	public function execute()
	{
		// authentication
		if(!Authentication::getLoggedInUser()) $this->redirect($this->url->buildUrl('forbidden', 'users'));

		// exists
		if(!CollectionsHelper::existsBySlug($this->url->getParameter(1)))
		{
			$this->redirect($this->url->buildUrl('index', 'error') . '?code=404&message=CollectionNotFoundError');
		}

		// fetch data
		$id = CollectionsHelper::getIdBySlug($this->url->getParameter(1));
		$this->collection = Collection::get($id);

		// logged in user vs collection user id
		if($this->collection->user_id != Authentication::getLoggedInUser()->id)
		{
			$this->redirect($this->url->buildUrl('index', 'error') . '?code=404&message=CollectionNotFoundError');
		}

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
				$this->redirect($this->url->buildUrl('index', null, null, array('report' => 'saved', 'var' => $this->collection->name)));
			}

			// show general error
			else $this->tpl->assign('form' . SpoonFilter::toCamelCase($this->frm->getName()) . 'HasError', true);
		}
	}
}
