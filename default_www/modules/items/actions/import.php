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

/*
		// insert collection
		require_once PATH_WWW . '/modules/collections/engine/model.php';
		$collection = new Collection();
		$collection->name = 'blah';
		$collection->save();
*/
		foreach($this->csv as $row)
		{
			// "regular" fields
			$item = new Item();
			$item->collection_id = 1; // $collection->id;
			$item->name = $row['name'];
			$item->description = isset($row['description']) ? $row['description'] : null;
			$item->image = isset($row['type']) ? $row['type'] : null; // @todo: process image
//			$item->save();
exit('blub');
			// "custom" fields
			// @todo
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
