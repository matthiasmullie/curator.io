<?php

/**
 * Collection
 *
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class Collection
{
	/**
	 * @var	int
	 */
	public $id, $user_id;

	/**
	 * @var	string
	 */
	public $description, $name, $uri;

	/**
	 * Delete the current collection.
	 */
	public function delete()
	{
		Site::getDB(true)->delete('collections', 'id = ?', $this->id);
		// @todo delete linked items
	}

	/**
	 * @param	int $id		The id of the user.
	 * @return Collection
	 */
	public static function get($id)
	{
		// redefine
		$id = (int) $id;

		// get data
		$data = Site::getDB()->getRecord(
			'SELECT i.id, i.user_id, i.name, i.description, i.uri
			 FROM collections AS i
			 WHERE i.id = ?',
			array($id)
		);

		// validate
		if($data === null) return false;

		// create instance
		$item = new Collection();

		// initialize
		$item->initialize($data);

		// return
		return $item;
	}

	/**
	 * Get an item by his uri
	 *
	 * @param string $uri
	 * @return boolean|Collection
	 */
	public static function getByUri($uri)
	{
		// redefine
		$uri = (string) $uri;

		// get data
		$data = Site::getDB()->getRecord(
				'SELECT i.id, i.user_id, i.name, i.description, i.uri
			 	 FROM collections AS i
			 	 WHERE i.uri = ?',
				array($uri)
		);

		// validate
		if($data === null) return false;

		// create instance
		$item = new Collection();

		// initialize
		$item->initialize($data);

		// return
		return $item;
	}

	/**
	 * Get a uniaue uri for a user
	 *
	 * @param string $uri
	 * @return string
	 */
	public static function getUniqueUri($uri)
	{
		$uri = preg_replace('/[^a-zA-Z0-9\s]/', '', $uri);
		$uri = SpoonFilter::urlise($uri);

		if(Site::getDB()->getVar('SELECT 1 FROM collections AS i WHERE i.uri = ?', array($uri)) == 1)
		{
			$uri = Site::addNumber($uri);
			return self::getUniqueUri($uri);
		}

		return $uri;
	}

	/**
	 * Initialize the object.
	 *
	 * @param	array $data		The data in an array.
	 * @return User
	 */
	public function initialize($data)
	{
		if(isset($data['id'])) $this->id = (int) $data['id'];
		if(isset($data['user_id'])) $this->user_id = (string) $data['user_id'];
		if(isset($data['name'])) $this->name = (string) $data['name'];
		if(isset($data['description'])) $this->description = (string) $data['description'];
		if(isset($data['uri'])) $this->uri = (string) $data['uri'];
	}

	/**
	 * Save the user
	 *
	 * @return bool
	 */
	public function save()
	{
		if($this->uri === null) $this->uri = self::getUniqueUri($this->name);

		// build record
		$item['user_id'] = $this->user_id;
		$item['name'] = $this->name;
		$item['description'] = $this->description;
		$item['uri'] = $this->uri;

		// non existing
		if($this->id === null)
		{
			$item['created_on'] = Site::getUTCDate();
			$this->id = Site::getDB(true)->insert('collections', $item);
		}

		else Site::getDB(true)->update('collections', $item, 'id = ?', $this->id);

		// return
		return true;
	}

	/**
	 * Return the object as an array
	 *
	 * @return array
	 */
	public function toArray()
	{
		// build array
		$item['id'] = $this->id;
		$item['user_id'] = $this->user_id;
		$item['name'] = $this->name;
		$item['description'] = $this->description;
		$item['uri'] = $this->uri;

		return $item;
	}
}

/**
 * Collection helper methods
 *
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class CollectionsHelper
{
	/**
	 * @return int
	 * @param string $slug
	 */
	public static function getIdBySlug($slug)
	{
		return (int) Site::getDB()->getVar('SELECT i.id FROM collections AS i WHERE i.uri = ?', array((string) $slug));
	}

	/**
	 * @return bool
	 * @param string $slug
	 */
	public static function existsBySlug($slug)
	{
		return (bool) Site::getDB()->getVar('SELECT 1 FROM collections AS i WHERE i.uri = ?', array((string) $slug));
	}
}
