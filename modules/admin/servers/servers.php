<?php


namespace IPS\axenserverlist\modules\admin\servers;

/* To prevent PHP errors (extending class does not exist) revealing path */

if (!\defined('\IPS\SUITE_UNIQUE_KEY')) {
	header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . ' 403 Forbidden');
	exit;
}

/**
 * servers
 */
class _servers extends \IPS\Node\Controller
{
	/**
	 * @brief	Has been CSRF-protected
	 */
	public static $csrfProtected = TRUE;

	/**
	 * Node Class
	 */
	protected $nodeClass = 'IPS\axenserverlist\Servers';

	/**
	 * Get Root Buttons
	 *
	 * @return	array
	 */
	public function _getRootButtons()
	{
		\IPS\Output::i()->sidebar['actions']['add'] = [
			'icon'	=> 'plus',
			'title'	=> 'menu__axenserverlist_servers_servers',
			'link'	=> $this->url->setQueryString('do', 'form'),
			'data'	=> [ 'ipsDialog' => '', 'ipsDialog-title' => \IPS\Member::loggedIn()->language()->addToStack('menu__axenserverlist_servers_servers') ],
			'primary' => true
		];
	}

	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Dispatcher::i()->checkAcpPermission('servers_manage');
		parent::execute();
	}
}
