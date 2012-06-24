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
	public $id, $user_id, $likes;

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
	 * Get a uniaue uri for a collection inside the scope of a user.
	 *
	 * @param string $userId
	 * @param string $uri
	 * @param int[optional] $ignoreId
	 * @return string
	 */
	public static function getUniqueUri($userId, $uri, $ignoreId = null)
	{
		$uri = preg_replace('/[^a-zA-Z0-9\-\s]/', '', $uri);
		$uri = SpoonFilter::urlise($uri);

		// uniquenessquery
		$query = 'SELECT 1 FROM collections AS i WHERE i.user_id = ? AND i.uri = ?';
		$parameters = array((int) $userId, $uri);
		if($ignoreId !== null)
		{
			$query .= ' AND i.id != ?';
			$parameters[] = (int) $ignoreId;
		}

		if(Site::getDB()->getVar($query, $parameters) == 1)
		{
			$uri = Site::addNumber($uri);
			return self::getUniqueUri($userId, $uri, $ignoreId);
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
		if(isset($data['likes'])) $this->likes = (int) $data['likes'];
	}

	/**
	 * Save the user
	 *
	 * @return bool
	 */
	public function save()
	{
		$this->uri = self::getUniqueUri($this->user_id, $this->name, $this->id);

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
		$user = User::get($this->user_id);

		// build array
		$item['id'] = $this->id;
		$item['user_id'] = $this->user_id;
		$item['user'] = $user->toArray();
		$item['name'] = $this->name;
		$item['description'] = $this->description;
		$item['uri'] = $this->uri;
		$item['full_uri'] = Spoon::get('url')->buildUrl('detail', 'collections') . '/' . $user->uri . '/' . $this->uri;
		$item['likes'] = $this->likes;
		// @todo	image

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
	 * Get collections orderd by creation date
	 *
	 * @return array
	 */
	public static function getOrderByCreatedOn($limit = 10)
	{
		$data = Site::getDB()->getRecords(
			'SELECT c.*, SUM(i.like_count) AS likes
			 FROM collections AS c
			 INNER JOIN items AS i ON c.id = i.collection_id
			 GROUP BY c.id
			 ORDER BY c.created_on DESC
			 LIMIT ?',
			array($limit)
		);

		$items = array();

		foreach($data as $row)
		{
			$collection = new Collection();
			$collection->initialize($row);

			$items[] = $collection;
		}

		return $items;
	}

	/**
	 * Get collections orderd by likes
	 *
	 * @return array
	 */
	public static function getOrderByLike($limit = 10)
	{
		$data = Site::getDB()->getRecords(
			'SELECT c.*, SUM(i.like_count) AS likes
			 FROM collections AS c
			 INNER JOIN items AS i ON c.id = i.collection_id
			 GROUP BY c.id
			 ORDER BY likes DESC
			 LIMIT ?',
			array($limit)
		);

		$items = array();

		foreach($data as $row)
		{
			$collection = new Collection();
			$collection->initialize($row);

			$items[] = $collection;
		}

		return $items;
	}

	/**
	 * @return int
	 * @param string $userSlug
	 * @param string $collectionSlug
	 */
	public static function getIdBySlug($userSlug, $collectionSlug)
	{
		return (int) Site::getDB()->getVar(
			'SELECT i.id
			 FROM collections AS i
			 INNER JOIN users AS u ON u.id = i.user_id
			 WHERE i.uri = ? AND u.uri = ?',
			array((string) $collectionSlug, (string) $userSlug)
		);
	}

	/**
	 * @return bool
	 * @param string $userSlug
	 * @param string $collectionSlug
	 */
	public static function existsBySlug($userSlug, $collectionSlug)
	{
		return (bool) (Site::getDB()->getVar(
			'SELECT 1
			 FROM collections AS i
			 INNER JOIN users AS u ON u.id = i.user_id
			 WHERE i.uri = ? AND u.uri = ?',
			array((string) $collectionSlug, (string) $userSlug)
		) == 1);
	}

	/**
	 * Get names that start with a given term for the autocomplete.
	 * Only names that exist more then once are used.
	 *
	 * @return array
	 * @param string $term
	 * @param int[optional] $limit
	 */
	public static function getNamesforAutocomplete($term, $limit = 20)
	{
		return (array) Site::getDB()->getColumn(
			'SELECT c.name, COUNT(c.name)
			 FROM collections AS c
			 WHERE c.name LIKE ?
			 GROUP BY c.name HAVING COUNT(c.name) > 1
			 ORDER BY c.name ASC
			 LIMIT ?',
			array((string) $term . '%', (int) $limit)
		);
	}

}
