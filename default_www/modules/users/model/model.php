<?php

/**
 * User
 *
 * @package		users
 * @subpackage	model
 *
 * @author 		Tijs Verkoyen <tijs@sumocoders.be>
 * @since		1.0
 */
class User
{
	/**
	 * The id of the user.
	 *
	 * @var	string
	 */
	public $id;

	/**
	 * Textual properties
	 *
	 * @var	string
	 */
	public $name, $uri, $facebookId;

	/**
	 * Delete an item from Facebook
	 *
	 * @param Item $item
	 */
	public function deleteItemFromFacebook(Item $item)
	{
		$facebook = Authentication::getFacebook();
		$url = Spoon::get('url');

		if(SPOON_DEBUG) $siteUrl = 'http://curator.io';
		else $siteUrl = SITE_URL;

		if($item->facebook_id != '')
		{
			$facebook->publish($item->facebook_id, array('method' => 'delete'));
		}

		$item->facebook_id = null;
		$item->save();
	}

	/**
	 * Get a user
	 *
	 * @param	int $id		The id of the user.
	 * @return User
	 */
	public static function get($id)
	{
		// redefine
		$id = (int) $id;

		// get data
		$data = Site::getDB()->getRecord('SELECT i.*
											FROM users AS i
											WHERE i.id = ?',
											array($id));

		// validate
		if($data === null) return false;

		// create instance
		$item = new User();

		// initialize
		$item->initialize($data);

		// return
		return $item;
	}

	/**
	 * Get a user
	 *
	 * @param string $url
	 * @return User
	 */
	public static function getByUri($uri)
	{
		$data = Site::getDB()->getRecord(
			'SELECT i.*
			 FROM users AS i
			 WHERE i.uri = ?',
			array((string) $uri)
		);

		// validate
		if($data === null) return false;

		// create instance
		$item = new User();
		return $item->initialize($data);
	}

	/**
	 * Get an user by his facebook ID
	 *
	 * @param int $id
	 * @return User
	 */
	public static function getByFacebookId($id)
	{
		$data = Site::getDB()->getRecord('SELECT i.*
											FROM users AS i
											WHERE facebook_id = ?',
											array($id));
		$user = new User();

		if($data === null)
		{
			// get data from Facebook
			$data = Authentication::getFacebook()->get('/me');

			// build record
			$user->name = $data['name'];
			$user->facebookId = $data['id'];

			$user->save();
		}

		else
		{
			$user->initialize($data);
		}

		return $user;
	}

	/**
	 * Get collections.
	 *
	 * @return array
	 */
	public function getCollections($limit = 100)
	{
		$data = (array) Site::getDB()->getRecords(
			'SELECT c.*, SUM(i.like_count) AS likes
			 FROM collections AS c
			 LEFT JOIN items AS i ON c.id = i.collection_id
			 WHERE c.user_id = ?
			 GROUP BY c.id
			 ORDER BY likes DESC
			 LIMIT ?',
			array($this->id, (int) $limit)
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
	 * Get total amount of collections for this user.
	 *
	 * @return int
	 */
	public function getCollectionCount()
	{
		return (int) Site::getDB()->getVar(
			'SELECT COUNT(*) FROM collections WHERE user_id = ?',
			$this->id
		);
	}

	/**
	 * Get total amount of items for this user.
	 *
	 * @return int
	 */
	public function getItemCount()
	{
		return (int) Site::getDB()->getVar(
			'SELECT COUNT(*)
			 FROM collections AS c
			 INNER JOIN items AS i ON i.collection_id = c.id
			 WHERE c.user_id = ?',
			$this->id
		);
	}

	/**
	 * Get total amount of likes for this user.
	 *
	 * @return int
	 */
	public function getLikeCount()
	{
		return (int) Site::getDB()->getVar(
			'SELECT SUM(i.like_count)
			 FROM collections AS c
			 INNER JOIN items AS i ON i.collection_id = c.id
			 WHERE c.user_id = ?',
			$this->id
		);
	}

	/**
	 * Get a unique uri for a user
	 *
	 * @param string $uri
	 * @return string
	 */
	protected function getUniqueUri($uri)
	{
		$uri = preg_replace('/[^a-zA-Z0-9\s]/', '', $uri);
		$uri = SpoonFilter::urlise($uri);

		if(Site::getDB()->getVar('SELECT 1
									FROM users AS i
									WHERE i.uri = ?',
									array($uri)) == 1)
		{
			$uri = Site::addNumber($uri);
			return $this->getUniqueUri($uri);
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
		if(isset($data['uri'])) $this->uri = (string) $data['uri'];
		if(isset($data['facebook_id'])) $this->facebookId = (string) $data['facebook_id'];
		if(isset($data['name'])) $this->name = (string) $data['name'];

		return $this;
	}

	/**
	 * Publish a collection to facebook
	 *
	 * @param Collection $collection
	 */
	public function publishItemToFacebook(Item $item)
	{
		$facebook = Authentication::getFacebook();
		$url = Spoon::get('url');

		if(SPOON_DEBUG) $siteUrl = 'http://curator.io';
		else $siteUrl = SITE_URL;

		$data = $facebook->publish('/me/curatorio:collect', array('item' => $siteUrl . $url->buildUrl('detail', 'items') . '/' . $item->uri));

		if(isset($data['id']))
		{
			$item->facebook_id = $data['id'];
			$item->save();
		}
	}

	/**
	 * Save the user
	 *
	 * @return bool
	 */
	public function save()
	{
		if($this->uri === null) $this->uri = $this->getUniqueUri($this->name);

		// build record
		$item['uri'] = $this->uri;
		$item['facebook_id'] = $this->facebookId;
		$item['name'] = $this->name;

		// non existing
		if($this->id === null)
		{
			$item['created_on'] = Site::getUTCDate();
			$this->id = Site::getDB(true)->insert('users', $item);
		}
		else Site::getDB(true)->update('users', $item, 'id = ?', $this->id);

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
		$url = Spoon::get('url');

		// build array
		$item['id'] = $this->id;
		$item['name'] = $this->name;
		$item['uri'] = $this->uri;
		$item['full_uri'] =  $url->buildUrl('detail', 'users') . '/' . $this->uri;
		$item['facebook_id'] = $this->facebookId;
		$item['avatar'] = 'http://graph.facebook.com/' . $this->facebookId . '/picture';
		$item['avatar_50x50'] = 'http://graph.facebook.com/' . $this->facebookId . '/picture?type=square';
		$item['avatar_x50'] = 'http://graph.facebook.com/' . $this->facebookId . '/picture?type=small';
		$item['avatar_x200'] = 'http://graph.facebook.com/' . $this->facebookId . '/picture?type=large';

		return $item;
	}
}
