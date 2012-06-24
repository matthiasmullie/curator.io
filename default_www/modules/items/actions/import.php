<?php

/**
 * This will import your collection in CSV into our sweet curator.io
 *
 * @package		items
 * @subpackage	import
 *
 * @author 		Matthias Mullie <matthias@mullie.eu>
 * @since		1.0
 */
class ItemsImport extends CuratorBaseAction
{
	/**
	 * Uploaded CSV-file in array format
	 *
	 * @var array
	 */
	protected $csv;

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
		$this->frm = new SpoonForm('import');
		$this->frm->addFile('csv');
	}

	/**
	 * Process incoming data
	 *
	 * @return void
	 */
	protected function processForm()
	{
		if(!$this->frm->isSubmitted() || !$this->frm->isCorrect()) return;

		// build & save collection
		require_once PATH_WWW . '/modules/collections/model/model.php';
		$this->collection = new Collection();
		$this->collection->name = preg_replace('/\.[^.]*?$/', '', $this->frm->getField('csv')->getFileName());
		$this->collection->user_id = $this->currentUser->id;
		$this->collection->save();

		// build & save item
		foreach($this->csv as $row)
		{
			$item = new Item();
			$item->collection_id = $this->collection->id;
			foreach($row as $key => $value) $item->$key = $value;
			$item->save();
		}

		// redirect to brand new collection
		$this->redirect($this->url->buildUrl('detail', 'collections') . '/' . $this->user->uri . '/' . $this->collection->uri);
	}

	/**
	 * Validate incoming data
	 *
	 * @return void
	 */
	protected function validateForm()
	{
		if(!$this->frm->isSubmitted()) return;

		$this->csv = $this->frm->getField('csv');

		// validate filled
		$this->csv->isFilled('Please upload a CSV file.');

		// validate type (CSV)
		if(!$this->csv->isAllowedExtension(array('csv'))) $this->csv->addError('Please upload a CSV file.');

		// valid format
		$this->csv = @SpoonFileCSV::fileToArray($this->csv->getTempFileName(), array(), null, ';');
		if(!$this->csv) $this->csv->addError('Please upload a valid (semicolon delimited) CSV file.');

		// mandatory field (name)
		if(!isset($this->csv[0]['name'])) $this->csv->addError('Please upload a CSV file with a column "name".');
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
