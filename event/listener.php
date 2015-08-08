<?php
/**
*
* @package phpBB Extension - Your post count
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\yourpostcount\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	public function __construct(\phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db)
	{
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.page_header_after'	=> 'page_header_after',
		);
	}

	public function page_header_after($event)
	{
		$sql = "SELECT user_posts
			FROM " . USERS_TABLE . "
			WHERE user_id = " . $this->user->data['user_id'];
		$result = $this->db->sql_query($sql);
		$post_self = (int) $this->db->sql_fetchfield('user_posts');
		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'SELF_POST_COUNT'	=> $post_self,
		));
	}
}