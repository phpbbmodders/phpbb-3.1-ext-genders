<?php
/**
*
* Genders extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Rich McGirr (RMcGirr83)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbmodders\genders\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/**
	* define our constants
	**/
	const GENDER_F = 2; // ladies first ;)
	const GENDER_M = 1;
	const GENDER_X = 0;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string phpBB root path */
	protected $phpbb_root_path;

	/** @var string phpEx */
	protected $php_ext;

	/**
	* the path to the images directory
	*
	*@var string
	*/
	protected $genders_path;

	public function __construct(
		\phpbb\request\request $request,
		\phpbb\template\template $template,
		\phpbb\user $user,
		$phpbb_root_path,
		$php_ext,
		$genders_path)
	{
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->images_path = $genders_path;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.ucp_profile_modify_profile_info'		=> 'user_gender_profile',
			'core.ucp_profile_validate_profile_info'	=> 'user_gender_profile_validate',
			'core.ucp_profile_info_modify_sql_ary'		=> 'user_gender_profile_sql',
			'core.viewtopic_cache_user_data'			=> 'viewtopic_cache_user_data',
			'core.viewtopic_cache_guest_data'			=> 'viewtopic_cache_guest_data',
			'core.viewtopic_modify_post_row'			=> 'viewtopic_modify_post_row',
			'core.memberlist_view_profile'				=> 'memberlist_view_profile',
			'core.search_get_posts_data'				=> 'search_get_posts_data',
			'core.search_modify_tpl_ary'				=> 'search_modify_tpl_ary',
		);
	}

	/**
	* Allow users to change their gender
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function user_gender_profile($event)
	{
		// Request the user option vars and add them to the data array
		$event['data'] = array_merge($event['data'], array(
			'user_gender'	=> $this->request->variable('gender', $this->user->data['user_gender']),
		));

		$this->user->add_lang_ext('phpbbmodders/genders', 'genders');

		$this->template->assign_vars(array(
			'GENDER_X'		=> self::GENDER_X,
			'GENDER_M'		=> self::GENDER_M,
			'GENDER_F'		=> self::GENDER_F,

			'S_GENDER_X'	=> ($event['data']['user_gender'] == self::GENDER_X) ? true : false,
			'S_GENDER_M'	=> ($event['data']['user_gender'] == self::GENDER_M) ? true : false,
			'S_GENDER_F'	=> ($event['data']['user_gender'] == self::GENDER_F) ? true : false,
		));
	}

	/**
	* Validate users changes to their gender
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function user_gender_profile_validate($event)
	{
			$array = $event['error'];
			//ensure gender is validated
			if (!function_exists('validate_data'))
			{
				include($this->root_path . 'includes/functions_user.' . $this->php_ext);
			}
			$validate_array = array(
				'user_gender'	=> array('num', true, 0, 2),
			);
			$error = validate_data($event['data'], $validate_array);
			$event['error'] = array_merge($array, $error);
	}

	/**
	* User changed their gender so update the database
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function user_gender_profile_sql($event)
	{
		$event['sql_ary'] = array_merge($event['sql_ary'], array(
				'user_gender' => $event['data']['user_gender'],
		));
	}

	/**
	* Update viewtopic user data
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_cache_user_data($event)
	{
		$array = $event['user_cache_data'];
		$array['user_gender'] = $event['row']['user_gender'];
		$event['user_cache_data'] = $array;
	}

	/**
	* Update viewtopic guest data
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_cache_guest_data($event)
	{
		$array = $event['user_cache_data'];
		$array['user_gender'] = 0;
		$event['user_cache_data'] = $array;
	}

	/**
	* Modify the viewtopic post row
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_modify_post_row($event)
	{
		$gender = $this->get_user_gender($event['user_poster_data']['user_gender']);

		$event['post_row'] = array_merge($event['post_row'],array(
			'USER_GENDER' => $gender,
		));
	}

	/**
	* Display gender on viewing user profile
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function memberlist_view_profile($event)
	{
		if (!empty($event['member']['user_gender']))
		{
			$gender = $this->get_user_gender($event['member']['user_gender']);

			$this->template->assign_vars(array(
				'USER_GENDER'	=> $gender,
			));
		}
	}

	/**
	* Display gender on search
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function search_get_posts_data($event)
	{
		$array = $event['sql_array'];
		$array['SELECT'] .= ', u.user_gender';
		$event['sql_array'] = $array;
	}

	/**
	* Display gender on search
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function search_modify_tpl_ary($event)
	{
		if ($event['show_results'] == 'topics')
		{
			return;
		}

		$array = $event['tpl_ary'];
		$gender = $this->get_user_gender($event['row']['user_gender']);
		$array = array_merge($array, array(
			'USER_GENDER'	=> $gender,
		));

		$event['tpl_ary'] = $array;
	}

	/**
	 * Get user gender
	 *
	 * @author RMcGirr83
	 * @author eviL3
	 * @param int $user_gender User's gender
	 * @return string Gender image
	 */
	private function get_user_gender($user_gender)
	{
		$this->user->add_lang_ext('phpbbmodders/genders', 'genders');

		switch ($user_gender)
		{
			case self::GENDER_M:
				$gender = 'gender_m';
			break;

			case self::GENDER_F:
				$gender = 'gender_f';
			break;

			default:
				$gender = 'gender_x';
		}

		$gender = '<img src="' . htmlspecialchars($this->root_path) . htmlspecialchars($this->images_path) . 'icon_' . $gender . '.gif" alt="' . $this->user->lang[strtoupper($gender)] . '" title="' . $this->user->lang[strtoupper($gender)] . '" />';

		return $gender;
	}
}
