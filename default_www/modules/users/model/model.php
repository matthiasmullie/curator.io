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
		$data = Site::getDB()->getRecord('SELECT i.id, i.name, i.email, i.secret, i.type, i.data
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

		if($data === null)
		{
			// get data from Facebook
			$data = Authentication::getFacebook()->get('/me');

			// build record
			$user = new User();
			$user->name = $data['name'];
			$user->facebookId = $data['id'];

			$user->save();
		}

		else
		{
			$user = User::initialize($data);
		}

		return $user;
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
	public static function initialize($data)
	{
		$user = new User();

		if(isset($data['id'])) $user->id = (int) $data['id'];
		if(isset($data['uri'])) $user->uri = (string) $data['uri'];
		if(isset($data['facebook_id'])) $user->facebookId = (string) $data['facebook_id'];
		if(isset($data['name'])) $user->name = (string) $data['name'];

		return $user;
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

		$facebook->publish('/me/curatorio:collect', array('item' =>  $url->buildUrl('items', 'detail') . '/' . $this->uri));
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
		$item['full_uri'] =  $url->buildUrl('users', 'detail') . '/' . $this->uri;
		$item['facebook_id'] = $this->facebookId;
		$item['avatar'] = 'http://graph.facebook.com/' . $this->facebookId . '/picture';
		$item['avatar_50x50'] = 'http://graph.facebook.com/' . $this->facebookId . '/picture?type=square';
		$item['avatar_x50'] = 'http://graph.facebook.com/' . $this->facebookId . '/picture?type=small';
		$item['avatar_x200'] = 'http://graph.facebook.com/' . $this->facebookId . '/picture?type=large';

		return $item;
	}
}
