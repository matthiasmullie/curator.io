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
	protected $price = 0;

	/**
	 * Amount of item's likes
	 *
	 * @var int
	 */
	protected $like_count = 0;

	/**
	 * Item's creation date
	 *
	 * @var date
	 */
	protected $created_on;

	/**
	 * Custom fields
	 *
	 * @var array
	 */
	protected $custom = array();

	/**
	 * Magic!
	 *
	 * @param string $property
	 */
	public function __get($property)
	{
		return $this->$property;
	}

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
				$value = $this->saveImage($value);
				$this->$property = $value;
				break;
			// new name = new uri
			case 'name':
				$this->uri = $value;
				$this->$property = $value;
				break;
			// urlize uri
			case 'uri':
				$this->$property = $this->getUniqueUri($value, $this->id);
				break;
			// just save the value
			default:
				// default value
				if(property_exists($this, $property) && $property != 'custom') $this->$property = $value;
				// custom value
				else $this->custom[$property] = $value;
				break;
		}
	}

	/**
	 * Get item object based on id
	 *
	 * @param int $id
	 */
	public static function get($id)
	{
		$array = Site::getDB(false)->getRecord(
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
	 * @param string $collectionUri
	 * @param string $userUri
	 */
	public static function getByUri($uri, $collectionUri, $userUri)
	{
		$array = Site::getDB(false)->getRecord(
			'SELECT i.*
			 FROM items AS i
			 INNER JOIN collections AS c ON c.id = i.collection_id
			 INNER JOIN users AS u ON u.id = c.user_id
			 WHERE i.uri = ? AND c.uri = ? AND u.uri = ?',
			array((string) $uri, (string) $collectionUri, (string) $userUri)
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
	protected function getUniqueUri($uri, $id = null)
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
			return $this->getUniqueUri($uri);
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
		if(!$array) return;

		// keys -> properties
		foreach($array as $key => $value) $this->$key = $value;

		// fetch custom fields
		$this->custom = Site::getDB(false)->getPairs(
			'SELECT name, value
			 FROM items_properties
			 WHERE item_id = ?
			 ORDER BY sequence ASC',
			array($this->id)
		);

		return $this;
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

		if($this->created_on === null) $this->created_on = Site::getUTCDate();
		$item = get_object_vars($this);
		unset($item['custom']);

		// update
		if($this->id !== null) $db->update('items', $item, 'id = ?', $this->id);

		// insert
		else $this->id = $db->insert('items', $item);

		// (re-)insert custom values
		$i = 0;
		$db->delete('items_properties', 'item_id = ?', array($this->id));
		foreach($this->custom as $name => $value)
		{
			$property = array(
				'item_id' => $this->id,
				'name' => $name,
				'value' => $value,
				'sequence' => $i++
			);
			$db->insert('items_properties', $property);
		}

		return true;
	}

	/**
	 * Save image - create thumbnails
	 *
	 * @param string $path
	 * @return string the filename
	 */
	protected function saveImage($image)
	{
		// check if uri/id/... already exists
		if($this->uri === null) throw new SpoonException('Please set name/uri before setting image.');

		// path to save
		$path = PATH_WWW . '/files/items';

		// file upload, use native methods to move file
		if($image instanceof SpoonFormImage)
		{
			$filename = $this->uri . '.' . $image->getExtension();
			$image->moveFile($path . '/source/' . $filename);
		}
		else
		{
			$filename = $this->uri . '.' . SpoonFile::getExtension($image);

			// if existing (local) file, move to desired path
			if(SpoonFile::exists($image))
			{
				SpoonFile::move($image, $path . '/source/' . $filename);
			}
			// if no existing (local) file, attempt to download the file
			else
			{
				$success = SpoonFile::download($image, $path . '/source/' . $filename);
				if(!$success) return false;
			}
		}

		// create thumbs - yay
		Site::generateThumbnails($path, $path . '/source/' . $filename);

		return $filename;
	}

	/**
	 * Convert the object into an array
	 *
	 * @return array
	 */
	public function toArray()
	{
		$return = get_object_vars($this);
		$return['full_uri'] = Spoon::get('url')->buildUrl('detail', 'items') . '/' . $this->uri;

		return $return;
	}
}