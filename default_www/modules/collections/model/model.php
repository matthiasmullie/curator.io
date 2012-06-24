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
	public $id, $category_id, $user_id, $created_on, $like_count;

	/**
	 * @var	string
	 */
	public $description, $name, $uri;

	/**
	 * Delete the current collection.
	 */
	public function delete()
	{
		// @todo delete linked items
		Site::getDB(true)->delete('collections', 'id = ?', $this->id);
	}

	/**
	 * Get an item by its id
	 *
	 * @param int $id
	 * @return Collection
	 */
	public static function get($id)
	{
		$array = Site::getDB()->getRecord(
			'SELECT c.*, UNIX_TIMESTAMP(c.created_on) AS created_on, SUM(i.like_count) AS like_count
		 	 FROM collections AS c
			 INNER JOIN items AS i ON i.collection_id = c.id
			 WHERE i.id = ?',
			array((int) $id)
		);

		$collection = new Collection();
		return $collection->initialize($array);
	}

	/**
	 * Get an item by its uri
	 *
	 * @param string $collectionUri
	 * @param string $userUri
	 * @return Collection
	 */
	public static function getByUri($collectionUri, $userUri)
	{
		$array = Site::getDB()->getRecord(
			'SELECT c.*, UNIX_TIMESTAMP(c.created_on) AS created_on, SUM(i.like_count) AS like_count
		 	 FROM collections AS c
			 INNER JOIN users AS u ON u.id = c.user_id
			 LEFT OUTER JOIN items AS i ON i.collection_id = c.id
		 	 WHERE c.uri = ? AND u.uri = ?
			 GROUP BY c.id',
			array((string) $collectionUri, (string) $userUri)
		);

		$collection = new Collection();
		return $collection->initialize($array);
	}

	/**
	 * Get a list of
	 *
	 * @param int $limit[optional]
	 * @param int $offset[optional]
	 * @return array
	 */
	public function getItems($limit = 99999999, $offset = 0)
	{
		$items = (array) Site::getDB(false)->getRecords(
			'SELECT i.*, UNIX_TIMESTAMP(i.created_on) AS created_on
			 FROM items AS i
			 INNER JOIN collections AS c ON c.id = i.collection_id
			 INNER JOIN users AS u ON u.id = c.user_id
			 WHERE c.uri = ?
			 LIMIT ?, ?',
			array($this->uri, $offset, $limit)
		);

		$result = array();
		foreach($items as $array)
		{
			$item = new Item();

			// note: this is so incredibly ugly (actually most of this is but hey - just dove in unprepared) but template engine only accepts array and right now, that's the only place we're using this
			$result[] = $item->initialize($array)->toArray();
		}

		return $result;
	}

	/**
	 * Get a unique uri for a collection inside the scope of a user.
	 *
	 * @param string $uri
	 * @param string $userUri
	 * @param int[optional] $ignoreId
	 * @return string
	 */
	public function getUniqueUri($uri, $userUri, $ignoreId = null)
	{
		$uri = preg_replace('/[^a-zA-Z0-9\s]/', '', $uri);
		$uri = SpoonFilter::urlise($uri);

		// spoof invalid id (if none given) to make query proceed
		if($ignoreId === null) $ignoreId = -1;

		$query =
			'SELECT 1
			 FROM collections AS c
			 INNER JOIN users AS u ON u.id = c.user_id
			 WHERE c.uri = ? AND u.uri = ? AND c.id != ?';

		if(Site::getDB()->getVar($query, array($uri, $userUri, $ignoreId)) == 1)
		{
			$uri = Site::addNumber($uri);
			return $this->getUniqueUri($uri, $userUri, $ignoreId);
		}

		return $uri;
	}

	/**
	 * Initialize the object.
	 *
	 * @param	array $array		The data in an array.
	 * @return Collection
	 */
	public function initialize($array)
	{
		if(!is_array($array) || !$array) return;

		// keys -> properties
		foreach($array as $key => $value) $this->$key = $value;

		// make sure properties are cast right
		$this->id = (int) $this->id;
		$this->user_id = (int) $this->user_id;
		$this->category_id = (int) $this->category_id;
		$this->created_on = (int) $this->created_on;
		$this->like_count = (int) $this->like_count;

		return $this;
	}

	/**
	 * Save the user
	 *
	 * @return bool
	 */
	public function save()
	{
		$this->uri = $this->getUniqueUri($this->name, $this->user_id, $this->id);

		// build record
		$item['user_id'] = $this->user_id;
		$item['category_id'] = $this->category_id;
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

		$return = get_object_vars($this);
		$return['full_uri'] = Spoon::get('url')->buildUrl('detail', 'collections') . '/' . $user->uri . '/' . $this->uri;
		$return['user'] = $user->toArray();

		return $return;
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
	 * @param int $categoryId
	 * @param int[optional] $limit
	 */
	public static function getByCategoryId($categoryId, $limit = 20)
	{
		$data = (array) Site::getDB()->getRecords(
			'SELECT c.*, SUM(i.like_count) AS likes
			 FROM collections AS c
			 INNER JOIN items AS i ON c.id = i.collection_id
			 WHERE c.category_id = ?
			 GROUP BY c.id
			 ORDER BY c.created_on DESC
			 LIMIT ?',
			array((int) $categoryId, (int) $limit)
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
	 * Get categories.
	 *
	 * @return array
	 */
	public static function getCategories()
	{
		$categories = (array) Site::getDB()->getRecords(
			'SELECT cc.id, cc.name, cc.uri
			 FROM collections_categories AS cc
			 INNER JOIN collections AS c ON c.category_id = cc.id
			 GROUP BY cc.id
			 ORDER BY cc.name ASC'
		);

		foreach($categories as &$category)
		{
			$category['full_uri'] = Spoon::get('url')->buildUrl('category', 'collections') . '/' . $category['uri'];
		}

		return $categories;
	}

	/**
	 * Get categories in key/value format.
	 *
	 * @return array
	 */
	public static function getCategoriesForDropdown()
	{
		return (array) Site::getDB()->getPairs(
			'SELECT id, name FROM collections_categories ORDER BY name ASC'
		);
	}

	/**
	 * Get category.
	 *
	 * @return array
	 * @param string $uri
	 */
	public static function getCategory($uri)
	{
		$category = (array) Site::getDB()->getRecord(
			'SELECT cc.id, cc.name, cc.uri
			 FROM collections_categories AS cc
			 INNER JOIN collections AS c ON c.category_id = cc.id
			 WHERE cc.uri = ?',
			array((string) $uri)
		);

		if(!empty($category))
		{
			$category['full_uri'] = Spoon::get('url')->buildUrl('category', 'collections') . '/' . $category['uri'];
		}

		return $category;
	}

	/**
	 * Get category.
	 *
	 * @return array
	 * @param int $id
	 */
	public static function getCategoryById($id)
	{
		$category = (array) Site::getDB()->getRecord(
			'SELECT cc.id, cc.name, cc.uri
			 FROM collections_categories AS cc
			 INNER JOIN collections AS c ON c.category_id = cc.id
			 WHERE cc.id = ?',
			array((int) $id)
		);

		if(!empty($category))
		{
			$category['full_uri'] = Spoon::get('url')->buildUrl('category', 'collections') . '/' . $category['uri'];
		}

		return $category;
	}

	/**
	 * Get collections orderd by creation date
	 *
	 * @return array
	 */
	public static function getOrderByCreatedOn($limit = 10)
	{
		$data = (array) Site::getDB()->getRecords(
			'SELECT c.*, SUM(i.like_count) AS like_count
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
		$data = (array) Site::getDB()->getRecords(
			'SELECT c.*, SUM(i.like_count) AS like_count
			 FROM collections AS c
			 INNER JOIN items AS i ON c.id = i.collection_id
			 GROUP BY c.id
			 ORDER BY like_count DESC
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
