<?php


namespace IPS\axenserverlist\modules\admin\servers;

/* To prevent PHP errors (extending class does not exist) revealing path */

if (!\defined('\IPS\SUITE_UNIQUE_KEY')) {
	header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . ' 403 Forbidden');
	exit;
}

/**
 * settings
 */
class _settings extends \IPS\Dispatcher\Controller
{
	/**
	 * @brief	Has been CSRF-protected
	 */
	public static $csrfProtected = TRUE;

	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Dispatcher::i()->checkAcpPermission('settings_manage');
		parent::execute();
	}

	/**
	 * ...
	 *
	 * @return	void
	 */
	protected function manage()
	{
		$form = new \IPS\Helpers\Form;
		$form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_settings_owners', \IPS\Settings::i()->aXenServerList_settings_owners, FALSE));

		if ($values = $form->values(TRUE)) {
			$form->saveAsSettings($values);
		}

		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('menu__axenserverlist_servers_settings');
		\IPS\Output::i()->output = $form;
	}

	// Create new methods with the same name as the 'do' parameter which should execute it
}
