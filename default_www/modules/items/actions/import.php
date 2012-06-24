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
class ItemsImport extends SiteBaseAction
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
		// user must be logged in
		if($this->currentUser === false) $this->redirect($this->url->buildUrl('forbidden', 'users') . '?redirect=' . urlencode($this->url->buildUrl('import')));

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

		$db = Site::getDB(true);

		// insert collection
		require_once PATH_WWW . '/modules/collections/model/model.php';
		$collection = new Collection();
		$collection->name = preg_replace('/\.[^.]*?$/', '', $this->frm->getField('csv')->getFileName());
		$collection->user_id = $this->currentUser->id;
		$collection->save();

		foreach($this->csv as $row)
		{
			$item = new Item();
			$item->collection_id = $collection->id;
			foreach($row as $key => $value) $item->$key = $value;
			$item->save();
		}
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
