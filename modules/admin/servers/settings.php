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
     * @brief    Has been CSRF-protected
     */
    public static $csrfProtected = true;

    /**
     * Execute
     *
     * @return    void
     */
    public function execute()
    {
        \IPS\Dispatcher::i()->checkAcpPermission('settings_manage');
        parent::execute();
    }

    /**
     * ...
     *
     * @return    void
     */
    protected function manage()
    {
        $form = new \IPS\Helpers\Form;
        $form->addTab('axenserverlist_tab_general');

        $form->addHeader('axenserverlist_header_colorFilling');
        $form->add(new \IPS\Helpers\Form\YesNo(
            'aXenServerList_settings_colors',
            \IPS\Settings::i()->aXenServerList_settings_colors,
            false,
            [
                'togglesOn' => [
                    'aXenServerList_settings_colors_1_20',
                    'aXenServerList_settings_colors_21_40',
                    'aXenServerList_settings_colors_41_60',
                    'aXenServerList_settings_colors_61_80',
                    'aXenServerList_settings_colors_81_100',
                ],
            ]
        ));
        $form->add(new \IPS\Helpers\Form\Color('aXenServerList_settings_colors_1_20', \IPS\Settings::i()->aXenServerList_settings_colors_1_20, false, [], null, null, null, 'aXenServerList_settings_colors_1_20'));
        $form->add(new \IPS\Helpers\Form\Color('aXenServerList_settings_colors_21_40', \IPS\Settings::i()->aXenServerList_settings_colors_21_40, false, [], null, null, null, 'aXenServerList_settings_colors_21_40'));
        $form->add(new \IPS\Helpers\Form\Color('aXenServerList_settings_colors_41_60', \IPS\Settings::i()->aXenServerList_settings_colors_41_60, false, [], null, null, null, 'aXenServerList_settings_colors_41_60'));
        $form->add(new \IPS\Helpers\Form\Color('aXenServerList_settings_colors_61_80', \IPS\Settings::i()->aXenServerList_settings_colors_61_80, false, [], null, null, null, 'aXenServerList_settings_colors_61_80'));
        $form->add(new \IPS\Helpers\Form\Color('aXenServerList_settings_colors_81_100', \IPS\Settings::i()->aXenServerList_settings_colors_81_100, false, [], null, null, null, 'aXenServerList_settings_colors_81_100'));

        $form->addHeader('axenserverlist_header_serverQuery');
        $form->add(new \IPS\Helpers\Form\Number('aXenServerList_settings_serverQuery_maxQuery', \IPS\Settings::i()->aXenServerList_settings_serverQuery_maxQuery, true, ['min' => 1, 'max' => 5]));

        $form->addTab('axenserverlist_tab_personalization');
        $form->add(new \IPS\Helpers\Form\YesNo(
            'aXenServerList_settings_fullWidth',
            \IPS\Settings::i()->aXenServerList_settings_fullWidth,
            false,
            [
                'togglesOn' => [
                    'aXenServerList_settings_fullWidth_control',
                ],
            ]
        ));

        $form->add(new \IPS\Helpers\Form\YesNo(
            'aXenServerList_settings_fullWidth_control',
            \IPS\Settings::i()->aXenServerList_settings_fullWidth_control,
            false,
            [
                'togglesOn' => [
                    'aXenServerList_settings_fullWidth_default',
                ],
            ],
            null,
            null,
            null,
            'aXenServerList_settings_fullWidth_control'
        ));
        $form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_settings_fullWidth_default', \IPS\Settings::i()->aXenServerList_settings_fullWidth_default, false, [], null, null, null, 'aXenServerList_settings_fullWidth_default'));
        $form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_settings_footer', \IPS\Settings::i()->aXenServerList_settings_footer));
        $form->add(new \IPS\Helpers\Form\YesNo(
            'aXenServerList_settings_table_group',
            \IPS\Settings::i()->aXenServerList_settings_table_group,
            false,
            [
                'togglesOn' => [
                    'aXenServerList_settings_table_group_icon',
                ],
            ],
            null,
            null,
            null,
            'aXenServerList_settings_table_group'
        ));

        $form->add(new \IPS\Helpers\Form\Radio('aXenServerList_settings_table_group_icon', \IPS\Settings::i()->aXenServerList_settings_table_group_icon, false, [
            'options' => [
                0 => 'aXenServerList_settings_table_group_icon_only_category',
                1 => 'aXenServerList_settings_table_group_icon_only_server',
                2 => 'aXenServerList_settings_table_group_icon_all',
            ],
        ], null, null, null, 'aXenServerList_settings_table_group_icon'));

        $buttonsType = [
            'ipsButton_normal' => 'ipsButton_normal',
            'ipsButton_primary' => 'ipsButton_primary',
            'ipsButton_alternate' => 'ipsButton_alternate',
            'ipsButton_important' => 'ipsButton_important',
            'ipsButton_light' => 'ipsButton_light',
            'ipsButton_veryLight' => 'ipsButton_veryLight',
        ];

        $form->addHeader('axenserverlist_header_buttons');
        $form->add(new \IPS\Helpers\Form\Select(
            'aXenServerList_settings_buttons_vote',
            \IPS\Settings::i()->aXenServerList_settings_buttons_vote,
            true,
            ['options' => $buttonsType, 'multiple' => false]
        ));
        $form->add(new \IPS\Helpers\Form\Select(
            'aXenServerList_settings_buttons_statistics',
            \IPS\Settings::i()->aXenServerList_settings_buttons_statistics,
            true,
            ['options' => $buttonsType, 'multiple' => false]
        ));
        $form->add(new \IPS\Helpers\Form\Select(
            'aXenServerList_settings_buttons_tv',
            \IPS\Settings::i()->aXenServerList_settings_buttons_tv,
            true,
            ['options' => $buttonsType, 'multiple' => false]
        ));
        $form->add(new \IPS\Helpers\Form\Select(
            'aXenServerList_settings_buttons_forum',
            \IPS\Settings::i()->aXenServerList_settings_buttons_forum,
            true,
            ['options' => $buttonsType, 'multiple' => false]
        ));

        $form->addTab('axenserverlist_tab_scroll');
        $form->add(new \IPS\Helpers\Form\YesNo(
            'aXenServerList_settings_scroll',
            \IPS\Settings::i()->aXenServerList_settings_scroll,
            false,
            [
                'togglesOn' => [
                    'aXenServerList_settings_scroll_height',
                    'aXenServerList_settings_scroll_default',
                    'aXenServerList_settings_scroll_control',
                    'aXenServerList_settings_scroll_mobile',
                ],
            ]
        ));
        $form->add(new \IPS\Helpers\Form\Number('aXenServerList_settings_scroll_height', \IPS\Settings::i()->aXenServerList_settings_scroll_height, false, [], null, null, null, 'aXenServerList_settings_scroll_height'));
        $form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_settings_scroll_default', \IPS\Settings::i()->aXenServerList_settings_scroll_default, false, [], null, null, null, 'aXenServerList_settings_scroll_default'));
        $form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_settings_scroll_control', \IPS\Settings::i()->aXenServerList_settings_scroll_control, false, [], null, null, null, 'aXenServerList_settings_scroll_control'));

        $form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_settings_scroll_mobile', \IPS\Settings::i()->aXenServerList_settings_scroll_mobile, false, [
            'togglesOn' => [
                'aXenServerList_settings_scroll_mobile_value',
            ],
        ], null, null, null, 'aXenServerList_settings_scroll_mobile'));
        $form->add(new \IPS\Helpers\Form\Number('aXenServerList_settings_scroll_mobile_value', \IPS\Settings::i()->aXenServerList_settings_scroll_mobile_value, false, [], null, null, null, 'aXenServerList_settings_scroll_mobile_value'));

        if ($values = $form->values(true)) {
            $form->saveAsSettings($values);

            \IPS\Output::i()->redirect(\IPS\Http\Url::internal('app=axenserverlist&module=servers&controller=settings'), 'saved');
        }

        \IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('menu__axenserverlist_servers_settings');
        \IPS\Output::i()->output = $form;
    }

    // Create new methods with the same name as the 'do' parameter which should execute it
}
