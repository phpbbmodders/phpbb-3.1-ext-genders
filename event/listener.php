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

	public function __construct(
			\phpbb\request\request $request,
			\phpbb\template\template $template,
			\phpbb\user $user,
			$phpbb_root_path,
			$php_ext)
	{
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
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
		$this->user->add_lang_ext('phpbbmodders/genders', 'genders');
		// Request the user option vars and add them to the data array
		$event['data'] = array_merge($event['data'], array(
			'user_gender'	=> $this->request->variable('user_gender', $this->user->data['user_gender']),
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
			//todo ensure gender is validated
			$array[] = '';
			$event['error'] = $array;
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
		$array['user_gender'] = '';
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
		//todo generate the image
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
			$this->user->add_lang_ext('phpbbmodders/genders', 'genders');

			$this->template->assign_vars(array(
				'USER_GENDER'	=> $gender,
				'S_GENDER'		=> true,
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
		$array = $event['tpl_ary'];

		$array = array_merge($array, array(
			'USER_GENDER'	=> $gender,
		));

		$event['tpl_ary'] = $array;
	}
}
