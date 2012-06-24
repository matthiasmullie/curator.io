<?php

/**
 * Item model
 *
 * @package		items
 * @subpackage	item
 *
 * @author 		Matthias Mullie <matthias@mullie.eu>
 * @since		1.0
 */
class Item
{
	/**
	 * Item's (AI) id
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * Items unique URI
	 *
	 * @var string
	 */
	protected $uri;

	/**
	 * Item's collection
	 *
	 * @var int
	 */
	protected $collection_id;

	/**
	 * The facebook id of the item
	 * @var unknown_type
	 */
	protected $facebook_id;

	/**
	 * Item's name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Item's description
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * Path to item's image
	 *
	 * @var string
	 */
	protected $image;

	/**
	 * Item's estimated price
	 *
	 * @var float
	 */
	protected $price;

	/**
	 * Amount of item's likes
	 *
	 * @var int
	 */
	protected $like_count;

	/**
	 * Item's creation date
	 *
	 * @var date
	 */
	protected $created_on;

	/**
	 * Magic!
	 *
	 * @return void
	 */
	public function __set($property, $value)
	{
		switch($property)
		{
			// write to file
			case 'image':
				// no image
				if($value === null) break;

				// @todo: check if local path or if remote
				if(!SpoonFile::exists($value))

				// remote path?
				SpoonFile::download($value, $destinationPath);


				Site::generateThumbnails($path, $image);
				break;
			// just save the value
			default:
				$this->$property = $value;
				break;
		}
	}


	public function __get($property)
	{
		return $this->$property;
	}

	/**
	 * Get item object based on id
	 *
	 * @param int $id
	 */
	public static function getById($id)
	{
		$array = Site::getDB(true)->getRecord(
			'SELECT *
			 FROM items
			 WHERE id = ?',
			array((int) $id)
		);

		$item = new Item();

		return $item->initialize($array);
	}

	/**
	 * Get item object based on uri
	 *
	 * @param string $uri
	 */
	public static function getByUri($uri)
	{
		$array = Site::getDB(true)->getRecord(
			'SELECT *
			 FROM items
			 WHERE uri = ?',
			array((string) $uri)
		);

		$item = new Item();

		return $item->initialize($array);
	}

	/**
	 * Get a unique uri for an item
	 *
	 * @param string $uri
	 * @param int[optional] $id
	 * @return string
	 */
	public static function getUniqueUri($uri, $id = null)
	{
		$uri = preg_replace('/[^a-zA-Z0-9\s]/', '', $uri);
		$uri = SpoonFilter::urlise($uri);

		// spoof invalid id (if none given) to make query proceed
		if($id === null) $id = -1;

		if(Site::getDB()->getVar('SELECT 1
									FROM items AS i
									WHERE i.uri = ? AND i.id != ?',
									array($uri, $id)) == 1)
		{
			$uri = Site::addNumber($uri);
			return self::getUniqueUri($uri);
		}

		return $uri;
	}

	/**
	 * Turn array into object
	 *
	 * @param array $array
	 * @return void
	 */
	protected function initialize($array)
	{
		$item = new Item();

		// keys -> properties
		foreach($array as $key => $value) $item->$key = $value;

		return $item;
	}

	/**
	 * Save the item to the db
	 *
	 * @return bool
	 */
	public function save()
	{
		// validate required fields
		if($this->name === null) return false;
		if($this->collection_id === null) return false;

		$db = Site::getDB(true);

		// get unique uri & creation date
		$this->uri = $this->getUniqueUri($this->name, $this->id);
		if($this->added_on === null) $this->added_on = Site::getUTCDate();

		// update
		if($this->id !== null) $db->update('items', get_object_vars($this));
		// insert
		else $this->id = $db->insert('items', get_object_vars($this));

		return true;
	}
}