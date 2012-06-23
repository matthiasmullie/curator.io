<?php

/**
 * This class handles authentication
 *
 * @package		site
 * @subpackage	core
 *
 * @author 		Tijs Verkoyen <tijs@sumocoders.be>
 * @since		1.0
 */
class Authentication
{
	/**
	 * Get the facebook instance
	 *
	 * @return Facebook
	 */
	public static function getFacebook()
	{
		return spoon::get('facebook');
	}

	/**
	 * Get the logged in user.
	 *
	 * @return User
	 */
	public static function getLoggedInUser()
	{
		// get db
		$db = Site::getDB(true);

		$data = self::getFacebook()->getCookie();

		// any data?
		if($data !== null)
		{
			// create instance
			return User::getByFacebookId($data['user_id']);
		}

		// no data, so redirect to login
		else
		{
			// reset session
			SpoonSession::destroy();
			session_regenerate_id(true);

			return false;
		}
	}

	/**
	 * Log out the user
	 *
	 * @return void
	 */
	public static function logout()
	{
		// delete session
		Site::getDB(true)->delete('users_sessions', 'session_id = ?', SpoonSession::getSessionId());

		// destroy
		SpoonSession::destroy();
		session_regenerate_id(true);
	}
}
