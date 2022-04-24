<?php

namespace IPS\axenserverlist;

class _Servers extends \IPS\Node\Model

{
    /**
     * Multiton Store
     */
    protected static $multitons = [];

    /**
     * Node Title
     */
    public static $nodeTitle = 'menu__axenserverlist_servers_servers';

    /**
     * Database Table
     */
    public static $databaseTable = 'axenserverlist_servers';
    public static $databaseColumnOrder = 'position';

    /**
     * Get URL
     *
     * @return    \IPS\Http\Url
     * @throws    \BadMethodCallException
     */
    public function url()
    {
        return;
    }

    /**
     * [Node] Add/Edit Form
     *
     * @param    \IPS\Helpers\Form    $form    The form
     * @return    void
     */
    public function form(&$form)
    {
        $members = [];
        if (!empty($this->owners)) {
            foreach (new \IPS\Patterns\ActiveRecordIterator(\IPS\Db::i()->select('*', 'core_members', array(\IPS\Db::i()->in('member_id', explode(",", $this->owners)))), 'IPS\Member') as $member) {
                $members[] = $member;
            }
        }

        $form->addTab('aXenServerList_admin_table_servers_tab_basic');
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_servers_name', $this->name, true));

        /* Get mods form Database */
        $mods = [];
        try {
            $mods = \IPS\Db::i()->select('*', 'axenserverlist_mods');
        } catch (\Exception$e) {
            \IPS\Output::i()->error('aXenServerList_error_not_mods_found', '(aXen) Advanced Server List/102', 404, '');
        }

        $modsInSelect = [];
        foreach ($mods as $mod) {
            $modsInSelect[$mod['id']] = "#" . $mod['id'] . ' - ' . $mod['name'];
        }
        $form->add(new \IPS\Helpers\Form\Select('aXenServerList_admin_table_servers_mod_id', $this->mod_id, true, ['options' => $modsInSelect, 'multiple' => false]));
        $form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_admin_table_servers_name_default', $this->name_default, false));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_servers_ip', $this->ip, true));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_servers_ip_custom', $this->ip_custom, false));
        $form->add(new \IPS\Helpers\Form\Number('aXenServerList_admin_table_servers_query_port', $this->query_port == 0 ? null : $this->query_port, false));

        $form->add(new \IPS\Helpers\Form\YesNo(
            'aXenServerList_admin_table_servers_custom_connect',
            $this->custom_connect,
            false,
            [
                'togglesOn' => [
                    'aXenServerList_admin_table_servers_custom_connect_link',
                ],
            ]
        ));
        $form->add(new \IPS\Helpers\Form\Url('aXenServerList_admin_table_servers_custom_connect_link', $this->custom_connect_link, false, [], null, null, null, 'aXenServerList_admin_table_servers_custom_connect_link'));

        $form->addTab('aXenServerList_admin_table_servers_tab_debug');
        $form->add(new \IPS\Helpers\Form\YesNo(
            'aXenServerList_admin_table_servers_debug',
            $this->debug,
            false,
            [
                'togglesOn' => [
                    'aXenServerList_admin_table_servers_debug_text_YesNo',
                ],
            ]
        ));

        $form->add(new \IPS\Helpers\Form\YesNo(
            'aXenServerList_admin_table_servers_debug_text_YesNo',
            $this->debug_text_YesNo,
            false,
            [
                'togglesOn' => [
                    'aXenServerList_admin_table_servers_debug_text',
                ],
            ],
            null,
            null,
            null,
            'aXenServerList_admin_table_servers_debug_text_YesNo'
        ));

        $form->add(new \IPS\Helpers\Form\Translatable('aXenServerList_admin_table_servers_debug_text', null, false, array('app' => 'axenserverlist', 'key' => "axenserverlist_debug_text_{$this->id}"), null, null, null, 'aXenServerList_admin_table_servers_debug_text'));

        $form->addTab('aXenServerList_admin_table_servers_tab_urls');
        $form->add(new \IPS\Helpers\Form\Url('aXenServerList_admin_table_servers_url_statistics', $this->url_statistics, false));
        $form->add(new \IPS\Helpers\Form\Url('aXenServerList_admin_table_servers_url_tv', $this->url_tv, false));
        $form->add(new \IPS\Helpers\Form\Url('aXenServerList_admin_table_servers_url_vote', $this->url_vote, false));
        $form->add(new \IPS\Helpers\Form\Url('aXenServerList_admin_table_servers_url_forum', $this->url_forum, false));

        $form->addTab('aXenServerList_admin_table_servers_tab_advanced');
        $form->add(new \IPS\Helpers\Form\Member('aXenServerList_admin_table_servers_owners', $members, false, array('multiple' => null)));
        $form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_admin_table_servers_new', $this->new, false));
        $form->add(new \IPS\Helpers\Form\YesNo(
            'aXenServerList_admin_table_servers_top_server',
            $this->top_server,
            false,
            array('togglesOn' => array(
                'aXenServerList_admin_table_servers_top_server_text',
            ))
        ));
        $form->add(new \IPS\Helpers\Form\Translatable('aXenServerList_admin_table_servers_top_server_text', null, false, array('app' => 'axenserverlist', 'key' => "axenserverlist_top_server_text_{$this->id}"), null, null, null, 'aXenServerList_admin_table_servers_top_server_text'));
    }

    /**
     * [Node] Format form values from add/edit form for save
     *
     * @param    array    $values    Values from the form
     * @return    array
     */
    public function formatFormValues($values)
    {
        if (!$this->id) {
            $this->save();
        }

        $_values = $values;
        $values = [];
        foreach ($_values as $k => $v) {
            if (mb_substr($k, 0, 35) === 'aXenServerList_admin_table_servers_') {
                $values[mb_substr($k, 35)] = $v;
            } else {
                $values[$k] = $v;
            }
        }

        // Save owners
        if (!empty($values['owners'])) {
            $members = [];
            foreach ($values['owners'] as $member) {
                $members[] = $member->member_id;
            }
            $values['owners'] = implode(',', $members);
        } else {
            $values['owners'] = null;
        }

        return $values;
    }

    /**
     * [Node] Perform actions after saving the form
     *
     * @param    array    $values    Values from the form
     * @return    void
     */
    public function postSaveForm($values)
    {
        /* Get mods form Database */
        $mods = [];
        try {
            $mods = \IPS\Db::i()->select('*', 'axenserverlist_mods');
        } catch (\Exception$e) {
            \IPS\Output::i()->error('aXenServerList_error_not_mods_found', '(aXen) Advanced Server List/105', 404, '');
        }

        // Save Translatable
        if (isset($values['top_server_text'])) {
            \IPS\Lang::saveCustom('axenserverlist', "axenserverlist_top_server_text_{$this->id}", $values['top_server_text']);
        } else {
            \IPS\Lang::deleteCustom('axenserverlist', "axenserverlist_top_server_text_{$this->id}");
        }

        if ($values['debug_text_YesNo']) {
            \IPS\Lang::saveCustom('axenserverlist', "axenserverlist_debug_text_{$this->id}", $values['debug_text']);
        } else {
            \IPS\Lang::deleteCustom('axenserverlist', "axenserverlist_debug_text_{$this->id}");
        }

        // Update server status
        $update = new \IPS\axenserverlist\Servers\Update;

        $server = [];
        try
        {
            $server = \IPS\Application::load('axenserverlist')->getFullDataServersTask($this->id);
        } catch (\Exception$e) {
            \IPS\Log::log($e, '(aXen) Advanced Server List - Server ID: ' . $this->id);
        }

        if ($server['mod_protocol'] == 'api' || $server['mod_protocol'] == 'discord') {
            $update->server($server, true);
        } else {
            $update->server($server);
        }

    }

    /**
     * [Node] Get Title
     *
     * @return    string
     */
    protected function get__title()
    {
        $getName = $this->name_default && $this->name_default_text ? $this->name_default_text : $this->name;
        $getIP = $this->ip_custom ? $this->ip_custom : $this->ip;

        return "#{$this->id} {$getName} - {$getIP}";
    }

    /**
     * [Node] Get Node Description
     *
     * @return    string|null
     */
    protected function get__description()
    {
        $mod = \IPS\Db::i()->select('id, name', 'axenserverlist_mods', ['id=?', $this->mod_id])->first();
        return '#' . $mod['id'] . ' - ' . $mod['name'];
    }

    /**
     * [Node] Return the custom badge for each row
     *
     * @return    NULL|array        Null for no badge, or an array of badge data (0 => CSS class type, 1 => language string, 2 => optional raw HTML to show instead of language string)
     */
    protected function get__badge()
    {
        if ($this->debug == true) {
            return [
                0 => 'ipsBadge ipsBadge_style2',
                1 => 'aXenServerList_admin_table_servers_tab_debug',
            ];
        } else if ($this->new == true) {
            return [
                0 => 'ipsBadge ipsBadge_negative',
                1 => 'aXenServerList_widget_new',
            ];
        }
    }

    /**
     * [Node] Get Icon for tree
     *
     * @note    Return the class for the icon (e.g. 'globe', the 'fa fa-' is added automatically so you do not need this here)
     * @return    string|null
     */
    protected function get__icon()
    {
        if ($this->top_server) {
            return 'trophy';
        }
    }

    /**
     * [Node] Get buttons to display in tree
     * Example code explains return value
     *
     * @param    string    $url        Base URL
     * @param    bool    $subnode    Is this a subnode?
     * @return    array
     */
    public function getButtons($url, $subnode = false)
    {
        $buttons = parent::getButtons($url, $subnode);

        $buttons['debug'] = array(
            'icon' => 'bug',
            'title' => 'aXenServerList_admin_table_servers_buttons_debug',
            'link' => \IPS\Http\Url::internal("app=axenserverlist&module=servers&controller=servers&do=debug&id={$this->id}"),
        );

        return $buttons;
    }

    /**
     * [ActiveRecord] Delete Record
     *
     * @return    void
     */
    public function delete()
    {
        foreach (['room_name' => "top_server_text_{$this->_id}", 'room_rules' => "debug_text_{$this->id}"] as $fieldKey => $langKey) {
            \IPS\Lang::deleteCustom('axenserverlist', $langKey);
        }

        return parent::delete();
    }
}
