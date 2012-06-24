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
	 * @var string
	 */
	protected $uri, $facebook_id, $name, $description, $image;

	/**
	 * @var float
	 */
	protected $price;

	/**
	 * @var int
	 */
	protected $id, $collection_id, $like_count, $created_on;

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
				$value = $this->saveImage($value);
				$this->$property = $value;
				break;
			// new name = new uri
			case 'name':
				// @todo: collection id must be set already
				$this->uri = $this->getUniqueUri($value, $this->id);
				$this->$property = $value;
				break;
			// urlize uri
			case 'uri':
				// @todo: collection id must be set already
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
	 * Delete an item
	 */
	public function delete()
	{
		// @todo: remove images
		Site::getDB(true)->delete('items', 'id = ?', array($this->id));
	}

	/**
	 * Get item object based on id
	 *
	 * @param int $id
	 * @return Item
	 */
	public static function get($id)
	{
		$array = Site::getDB(false)->getRecord(
			'SELECT i.*, UNIX_TIMESTAMP(i.created_on) AS created_on
			 FROM items AS i
			 WHERE i.id = ?',
			array((int) $id)
		);

		$item = new Item();
		return $item->initialize($array);
	}

	/**
	 * Get item object based on uri
	 *
	 * @param string $itemUri
	 * @param string $collectionUri
	 * @param string $userUri
	 * @return Item
	 */
	public static function getByUri($itemUri, $collectionUri, $userUri)
	{
		$array = Site::getDB(false)->getRecord(
			'SELECT i.*, UNIX_TIMESTAMP(i.created_on) AS created_on
			 FROM items AS i
			 INNER JOIN collections AS c ON c.id = i.collection_id
			 INNER JOIN users AS u ON u.id = c.user_id
			 WHERE i.uri = ? AND c.uri = ? AND u.uri = ?',
			array((string) $itemUri, (string) $collectionUri, (string) $userUri)
		);

		$item = new Item();
		return $item->initialize($array);
	}

	/**
	 * Get a unique uri for an item
	 *
	 * @param string $uri
	 * @param string $collectionUri
	 * @param int[optional] $id
	 * @return string
	 */
	protected function getUniqueUri($uri, $collectionUri, $ignoreId = null)
	{
		$uri = preg_replace('/[^a-zA-Z0-9\s]/', '', $uri);
		$uri = SpoonFilter::urlise($uri);

		// spoof invalid id (if none given) to make query proceed
		if($ignoreId === null) $ignoreId = -1;

		$query =
			'SELECT 1
			 FROM items AS i
			 INNER JOIN collections AS c ON c.id = i.collection_id
			 WHERE i.uri = ? AND c.uri = ? AND i.id != ?';

		if(Site::getDB()->getVar($query, array($uri, $collectionUri, $ignoreId)) == 1)
		{
			$uri = Site::addNumber($uri);
			return $this->getUniqueUri($uri, $collectionUri, $ignoreId);
		}

		return $uri;
	}

	/**
	 * Turn array into object
	 *
	 * @param array $array
	 * @return Item
	 */
	public function initialize($array)
	{
		if(!is_array($array) || !$array) return;

		// keys -> properties
		foreach($array as $key => $value) $this->$key = $value;

		// make sure properties are cast right
		$this->id = (int) $this->id;
		$this->collection_id = (int) $this->collection_id;
		$this->like_count = (int) $this->like_count;
		$this->created_on = (int) $this->created_on;
		$this->price = (float) $this->price;

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
		if($image === null) return null;

		// check if uri/id/... already exists
		if($this->uri === null) throw new SpoonException('Please set name/uri before setting image.');

		// path to save
		$path = PATH_WWW . '/files/items';

		// file upload, use native methods to move file
		if($image instanceof SpoonFormImage)
		{
			if(!$image->isFilled()) return null;
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
		$collection = Collection::get($this->collection_id);
		$user = User::get($collection->user_id);

		$return = get_object_vars($this);
		$return['full_uri'] = Spoon::get('url')->buildUrl('detail', 'items') . '/' . $user->uri . '/' . $collection->uri . '/' . $this->uri;
		$return['collection'] = $collection->toArray();

		return $return;
	}
}
