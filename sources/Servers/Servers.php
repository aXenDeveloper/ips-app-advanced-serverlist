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

        // $form->add(new \IPS\Helpers\Form\Select('aXenServerList_admin_table_servers_game', $this->game, true, array('options' => array(
        //     'arkse' => 'ARK: Survival Evolved',
        //     'arma3' => 'Arma3',
        //     'bf2' => 'Battlefield 2',
        //     'bf3' => 'Battlefield 3',
        //     'bf4' => 'Battlefield 4',
        //     'bf1942' => 'Battlefield 1942',
        //     'bfbc2' => 'Battlefield Bad Company 2',
        //     'bfh' => 'Battlefield Hardline',
        //     'cod' => 'Call of Duty',
        //     'cod2' => 'Call of Duty 2',
        //     'cod4' => 'Call of Duty 4',
        //     'coduo' => 'Call of Duty: United Offensive',
        //     'codwaw' => 'Call of Duty: World at War',
        //     'conanexiles' => 'Conan Exiles',
        //     'contagion' => 'Contagion',
        //     'cs16' => "Counter-Strike 1.6",
        //     'cscz' => 'Counter-Strike: Condition Zero',
        //     'csgo' => "Counter-Strike: Global Offensive",
        //     'css' => 'Counter-Strike: Source',
        //     'dayz' => 'DayZ Standalone',
        //     'dayzmod' => 'DayZ Mod',
        //     'discord' => 'Discord',
        //     'gmod' => "Garry's Mod",
        //     'grav' => 'GRAV Online',
        //     'gta5m' => 'GTA Five M',
        //     'gtan' => 'Grand Theft Auto Network',
        //     'hl2dm' => 'Half Life 2: Deathmatch',
        //     'hurtworld' => 'Hurtworld',
        //     'insurgency' => 'Insurgency',
        //     'jediacademy' => 'Star Wars Jedi Knight: Jedi Academy',
        //     'jedioutcast' => 'Star Wars Jedi Knight II: Jedi Outcast',
        //     'justcause2' => 'Just Cause 2 Multiplayer',
        //     'justcause3' => 'Just Cause 3',
        //     'killingfloor' => 'Killing Floor',
        //     'killingfloor2' => 'Killing Floor 2',
        //     'l4d' => 'Left 4 Dead',
        //     'l4d2' => 'Left 4 Dead 2',
        //     'minecraft' => "Minecraft",
        //     'mohaa' => 'Medal of honor: Allied Assault',
        //     'mta' => 'Multi Theft Auto',
        //     'mumble' => 'Mumble Server',
        //     'ns2' => 'Natural Selection 2',
        //     'quake2' => 'Quake 2 Server',
        //     'quake3' => 'Quake 3 Server',
        //     'quakelive' => 'Quake Live',
        //     'redorchestra2' => 'Red Orchestra 2',
        //     'rust' => 'Rust',
        //     'samp' => 'San Andreas Multiplayer',
        //     'sevendaystodie' => '7 Days to Die',
        //     'ship' => 'The Ship',
        //     'squad' => 'Squad',
        //     'starmade' => 'StarMade',
        //     'teamspeak3' => "Teamspeak 3",
        //     'teeworlds' => 'Teeworlds Server',
        //     'terraria' => 'Terraria',
        //     'tf2' => 'Team Fortress 2',
        //     'tibia' => 'Tibia',
        //     'tshock' => 'Tshock',
        //     'unreal2' => 'Unreal 2',
        //     'unturned' => 'Unturned',
        //     'ut3' => 'Unreal Tournament 3',
        //     'ut2004' => 'Unreal Tournament 2004',
        //     'valheim' => 'Valheim',
        //     'ventrilo' => 'Ventrilo',
        //     'warsow' => 'Warsow',
        //     'won' => 'World Opponent Network',
        // ), 'multiple' => false)));
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

        require_once \IPS\Application::getRootPath() . '/applications/axenserverlist/sources/GameQ/Autoloader.php';

        $gq = new \GameQ\GameQ();
        $gq->setOption('write_wait', 10);
        $gq->setOption('timeout', 3);

        if ($values['game'] != 'discord') {
            $server = [
                'id' => $this->id,
                'type' => $this->game,
                'host' => $this->ip,
            ];

            if ($values['query_port']) {
                $server['options'] = [
                    'query_port' => $values['query_port'],
                ];
            };

            try {
                $gq->clearServers();
                $gq->addServer($server);

                $results = $gq->process();

                foreach ($results as $id => $data) {
                    if ($data['gq_online'] == true) {
                        $dataUpdate = [
                            'status' => 1,
                            'current_players' => $data['gq_numplayers'],
                            'max_players' => $data['gq_maxplayers'],
                            'name_default_text' => $data['gq_hostname'],
                            'map' => isset($data['gq_mapname']) ? $data['gq_mapname'] : null,
                            'game_long' => $data['gq_name'],
                            'connect_link' => $data['gq_joinlink'],
                            'protocol' => $data['gq_protocol'],
                            'password' => $data['gq_password'],
                        ];

                        if ($data['gq_numplayers'] > $this->most_players) {
                            $dataUpdate['most_players'] = $data['gq_numplayers'];
                        }

                        \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['id=?', $this->id]);
                    } else {
                        $dataUpdate = [
                            'status' => 0,
                            'current_players' => 0,
                            'max_players' => 0,
                            'map' => null,
                            'game_long' => $data['gq_name'],
                            'connect_link' => $data['gq_joinlink'],
                            'protocol' => $data['gq_protocol'],
                            'password' => $data['gq_password'],
                        ];

                        \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['id=?', $this->id]);
                    }
                }
            } catch (\Exception$e) {
                \IPS\Log::log($e, '(aXen) Advanced Server List - Server ID: ' . $server['id']);
            }
        } else {
            try {
                $url = "https://discordapp.com/api/guilds/" . $values['ip'] . "/widget.json";
                $dataFromJSON = \IPS\Http\Url::external($url)->request()->get()->decodeJson();

                if (!$dataFromJSON['name']) {
                    $dataUpdate = [
                        'status' => 0,
                        'current_players' => 0,
                        'max_players' => 0,
                        'game_long' => 'Discord',
                        'protocol' => 'discord',
                    ];
                }

                $dataUpdate = [
                    'status' => 1,
                    'current_players' => $dataFromJSON['presence_count'],
                    'max_players' => $dataFromJSON['presence_count'],
                    'name_default_text' => $dataFromJSON['name'],
                    'game_long' => 'Discord',
                    'connect_link' => $dataFromJSON['instant_invite'],
                    'protocol' => 'discord',
                ];

                if ($dataFromJSON['presence_count'] > $this->most_players) {
                    $dataUpdate['most_players'] = $dataFromJSON['presence_count'];
                }

                \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['id=?', $this->id]);
            } catch (\Exception$e) {
                \IPS\Log::log($e, '(aXen) Advanced Server List - Server ID: ' . $server['id']);
            }
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

        return "{$getName} - {$getIP}";
    }

    /**
     * [Node] Get Node Description
     *
     * @return    string|null
     */
    protected function get__description()
    {
        return \IPS\Db::i()->select('name', 'axenserverlist_mods', ['id=?', $this->mod_id])->first();
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
