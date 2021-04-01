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
		$form->addTab('axenserverlist_tab_general');

		$form->addHeader('axenserverlist_header_colorFilling');
		$form->add(new \IPS\Helpers\Form\YesNo(
			'aXenServerList_settings_colors',
			\IPS\Settings::i()->aXenServerList_settings_colors,
			FALSE,
			[
				'togglesOn' => [
					'aXenServerList_settings_colors_1_20',
					'aXenServerList_settings_colors_21_40',
					'aXenServerList_settings_colors_41_60',
					'aXenServerList_settings_colors_61_80',
					'aXenServerList_settings_colors_81_100'
				]
			]
		));
		$form->add(new \IPS\Helpers\Form\Color('aXenServerList_settings_colors_1_20', \IPS\Settings::i()->aXenServerList_settings_colors_1_20, FALSE, [], NULL, NULL, NULL, 'aXenServerList_settings_colors_1_20'));
		$form->add(new \IPS\Helpers\Form\Color('aXenServerList_settings_colors_21_40', \IPS\Settings::i()->aXenServerList_settings_colors_21_40, FALSE, [], NULL, NULL, NULL, 'aXenServerList_settings_colors_21_40'));
		$form->add(new \IPS\Helpers\Form\Color('aXenServerList_settings_colors_41_60', \IPS\Settings::i()->aXenServerList_settings_colors_41_60, FALSE, [], NULL, NULL, NULL, 'aXenServerList_settings_colors_41_60'));
		$form->add(new \IPS\Helpers\Form\Color('aXenServerList_settings_colors_61_80', \IPS\Settings::i()->aXenServerList_settings_colors_61_80, FALSE, [], NULL, NULL, NULL, 'aXenServerList_settings_colors_61_80'));
		$form->add(new \IPS\Helpers\Form\Color('aXenServerList_settings_colors_81_100', \IPS\Settings::i()->aXenServerList_settings_colors_81_100, FALSE, [], NULL, NULL, NULL, 'aXenServerList_settings_colors_81_100'));


		$form->addTab('axenserverlist_tab_personalization');
		$form->add(new \IPS\Helpers\Form\YesNo(
			'aXenServerList_settings_fullWidth',
			\IPS\Settings::i()->aXenServerList_settings_fullWidth,
			FALSE,
			[
				'togglesOn' => [
					'aXenServerList_settings_fullWidth_control'
				]
			]
		));

		$form->add(new \IPS\Helpers\Form\YesNo(
			'aXenServerList_settings_fullWidth_control',
			\IPS\Settings::i()->aXenServerList_settings_fullWidth_control,
			FALSE,
			[
				'togglesOn' => [
					'aXenServerList_settings_fullWidth_default'
				]
			],
			NULL,
			NULL,
			NULL,
			'aXenServerList_settings_fullWidth_control'
		));
		$form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_settings_fullWidth_default', \IPS\Settings::i()->aXenServerList_settings_fullWidth_default, FALSE, [], NULL, NULL, NULL, 'aXenServerList_settings_fullWidth_default'));
		$form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_settings_footer', \IPS\Settings::i()->aXenServerList_settings_footer, FALSE));

		$buttonsType = [
			'ipsButton_normal' => 'ipsButton_normal',
			'ipsButton_primary' => 'ipsButton_primary',
			'ipsButton_alternate' => 'ipsButton_alternate',
			'ipsButton_important' => 'ipsButton_important',
			'ipsButton_light' => 'ipsButton_light',
			'ipsButton_veryLight' => 'ipsButton_veryLight'
		];

		$form->addHeader('axenserverlist_header_buttons');
		$form->add(new \IPS\Helpers\Form\Select(
			'axenserverlist_settings_buttons_vote',
			\IPS\Settings::i()->axenserverlist_settings_buttons_vote,
			TRUE,
			['options' => $buttonsType, 'multiple' => FALSE]
		));
		$form->add(new \IPS\Helpers\Form\Select(
			'axenserverlist_settings_buttons_statistics',
			\IPS\Settings::i()->axenserverlist_settings_buttons_statistics,
			TRUE,
			['options' => $buttonsType, 'multiple' => FALSE]
		));
		$form->add(new \IPS\Helpers\Form\Select(
			'axenserverlist_settings_buttons_tv',
			\IPS\Settings::i()->axenserverlist_settings_buttons_tv,
			TRUE,
			['options' => $buttonsType, 'multiple' => FALSE]
		));
		$form->add(new \IPS\Helpers\Form\Select(
			'axenserverlist_settings_buttons_forum',
			\IPS\Settings::i()->axenserverlist_settings_buttons_forum,
			TRUE,
			['options' => $buttonsType, 'multiple' => FALSE]
		));

		$form->addTab('axenserverlist_tab_scroll');
		$form->add(new \IPS\Helpers\Form\YesNo(
			'aXenServerList_settings_scroll',
			\IPS\Settings::i()->aXenServerList_settings_scroll,
			FALSE,
			[
				'togglesOn' => [
					'aXenServerList_settings_scroll_height',
					'aXenServerList_settings_scroll_default',
					'aXenServerList_settings_scroll_control',
					'aXenServerList_settings_scroll_mobile'
				]
			]
		));
		$form->add(new \IPS\Helpers\Form\Number('aXenServerList_settings_scroll_height', \IPS\Settings::i()->aXenServerList_settings_scroll_height, FALSE, [], NULL, NULL, NULL, 'aXenServerList_settings_scroll_height'));
		$form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_settings_scroll_default', \IPS\Settings::i()->aXenServerList_settings_scroll_default, FALSE, [], NULL, NULL, NULL, 'aXenServerList_settings_scroll_default'));
		$form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_settings_scroll_control', \IPS\Settings::i()->aXenServerList_settings_scroll_control, FALSE, [], NULL, NULL, NULL, 'aXenServerList_settings_scroll_control'));

		$form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_settings_scroll_mobile', \IPS\Settings::i()->aXenServerList_settings_scroll_mobile, FALSE, [
			'togglesOn' => [
				'aXenServerList_settings_scroll_mobile_value'
			]
		], NULL, NULL, NULL, 'aXenServerList_settings_scroll_mobile'));
		$form->add(new \IPS\Helpers\Form\Number('aXenServerList_settings_scroll_mobile_value', \IPS\Settings::i()->aXenServerList_settings_scroll_mobile_value, FALSE, [], NULL, NULL, NULL, 'aXenServerList_settings_scroll_mobile_value'));

		if ($values = $form->values(TRUE)) {
			$form->saveAsSettings($values);

			\IPS\Output::i()->redirect(\IPS\Http\Url::internal('app=axenserverlist&module=servers&controller=settings'), 'saved');
		}

		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('menu__axenserverlist_servers_settings');
		\IPS\Output::i()->output = $form;
	}

	// Create new methods with the same name as the 'do' parameter which should execute it
}
