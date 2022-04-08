<?php

namespace IPS\axenserverlist\modules\admin\servers;

/* To prevent PHP errors (extending class does not exist) revealing path */
if (!\defined('\IPS\SUITE_UNIQUE_KEY')) {
    header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . ' 403 Forbidden');
    exit;
}

/**
 * mods
 */
class _mods extends \IPS\Dispatcher\Controller

{
    /**
     * @brief    Has been CSRF-protected
     */
    public static $csrfProtected = true;

    /**
     * @brief    Database Table
     */
    protected $table = 'axenserverlist_mods';

    /**
     * Execute
     *
     * @return    void
     */
    public function execute()
    {
        \IPS\Dispatcher::i()->checkAcpPermission('mods_manage');
        parent::execute();
    }

    /**
     * ...
     *
     * @return    void
     */
    protected function manage()
    {
        $url = \IPS\Http\Url::internal('app=axenserverlist&module=servers&controller=mods');

        /* Create the table */
        $table = new \IPS\Helpers\Table\Db($this->table, $url);
        $table->langPrefix = 'aXenServerList_admin_table_mods_';

        /* Columns we need */
        $table->include = ['icon', 'name', 'protocol', 'api_url', 'api_current_players', 'api_max_players', 'api_password', 'api_map', 'api_platform'];
        $table->mainColumn = 'name';

        /* Default sort options */
        $table->sortBy = $table->sortBy ?: 'name';
        $table->noSort = ['icon', 'protocol', 'api_url', 'api_current_players', 'api_max_players', 'api_password', 'api_map', 'api_platform'];
        $table->quickSearch = 'name';

        $table->parsers = [
            'icon' => function ($val) {
                return '<img src="' . \IPS\File::get($this->table, $val)->url . '" alt="" />';
            },
        ];

        $table->rowButtons = function ($row) {
            $return = [];

            $return['edit'] = array(
                'icon' => 'pencil',
                'title' => 'edit',
                'link' => \IPS\Http\Url::internal('app=axenserverlist&module=servers&controller=mods&do=form&id=' . $row['id']),
                'data' => ['ipsDialog' => '', 'ipsDialog-title' => $row['name']],
            );

            $return['delete'] = array(
                'icon' => 'times-circle',
                'title' => 'delete',
                'link' => \IPS\Http\Url::internal('app=axenserverlist&module=servers&controller=mods&do=delete&id=' . $row['id']),
                'data' => ['delete' => '', 'delete-warning' => ''],
            );

            return $return;
        };

        \IPS\Output::i()->sidebar['actions']['add'] = array(
            'primary' => true,
            'icon' => 'plus',
            'title' => 'aXenServerList_admin_table_mods_new',
            'link' => \IPS\Http\Url::internal('app=axenserverlist&module=servers&controller=mods&do=form'),
            'data' => ['ipsDialog' => '', 'ipsDialog-title' => \IPS\Member::loggedIn()->language()->addToStack('aXenServerList_admin_table_mods_new')],
        );

        /* Display */
        \IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('menu__axenserverlist_servers_mods');
        \IPS\Output::i()->output = \IPS\Theme::i()->getTemplate('global', 'core')->block('menu__axenserverlist_servers_mods', (string) $table);
    }

    /**
     * Add/Edit Mod
     *
     * @return    void
     */
    public function form()
    {
        $id = \IPS\Request::i()->id;

        /* Get data form Database */
        $item = [];
        try {
            $item = \IPS\Db::i()->select('*', $this->table, ['id = ?', $id])->first();
        } catch (\Exception$e) {
            if (\IPS\Request::i()->id) {
                \IPS\Output::i()->error('page_not_found', '(aXen) Advanced Server List/101', 404, '');
            }
        }

        /* Build form */
        $form = new \IPS\Helpers\Form;
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_name', $item ? $item['name'] : '', true));
        $form->add(new \IPS\Helpers\Form\Upload('aXenServerList_admin_table_mods_icon', $item && $item['icon'] ? \IPS\File::get($this->table, $item['icon']) : null, false, ['image' => true, 'storageExtension' => $this->table]));
        $form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_admin_table_mods_api', $item ? $item['api'] : '', false,
            [
                'togglesOff' => ['aXenServerList_admin_table_mods_protocol'],
                'togglesOn' => [
                    'aXenServerList_admin_table_mods_api_url',
                    'aXenServerList_admin_table_mods_api_current_players',
                    'aXenServerList_admin_table_mods_api_max_players',
                    'aXenServerList_admin_table_mods_api_password',
                    'aXenServerList_admin_table_mods_api_map',
                    'aXenServerList_admin_table_mods_api_platform',
                ],
            ]
        ));

        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_api_url', $item ? $item['api_url'] : '', true, [], null, null, null, 'aXenServerList_admin_table_mods_api_url'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_api_current_players', $item ? $item['api_current_players'] : '', false, [], null, null, null, 'aXenServerList_admin_table_mods_api_current_players'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_api_max_players', $item ? $item['api_max_players'] : '', false, [], null, null, null, 'aXenServerList_admin_table_mods_api_max_players'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_api_password', $item ? $item['api_password'] : '', false, [], null, null, null, 'aXenServerList_admin_table_mods_api_password'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_api_map', $item ? $item['api_map'] : '', false, [], null, null, null, 'aXenServerList_admin_table_mods_api_map'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_api_platform', $item ? $item['api_platform'] : '', false, [], null, null, null, 'aXenServerList_admin_table_mods_api_platform'));

        $form->add(new \IPS\Helpers\Form\Select('aXenServerList_admin_table_mods_protocol', $item ? $item['protocol'] : '', false, ['options' => [
            'aa3' => "America's Army 3",
            'aapg' => "America's Army: Proving Grounds",
            'arkse' => 'ARK: Survival Evolved',
            'arma3' => 'Arma3',
            'api' => "API",
            'bf2' => 'Battlefield 2',
            'bf3' => 'Battlefield 3',
            'bf4' => 'Battlefield 4',
            'bf1942' => 'Battlefield 1942',
            'bfbc2' => 'Battlefield Bad Company 2',
            'bfh' => 'Battlefield Hardline',
            'cod' => 'Call of Duty',
            'cod2' => 'Call of Duty 2',
            'cod4' => 'Call of Duty 4',
            'coduo' => 'Call of Duty: United Offensive',
            'codwaw' => 'Call of Duty: World at War',
            'conanexiles' => 'Conan Exiles',
            'contagion' => 'Contagion',
            'cs16' => "Counter-Strike 1.6",
            'cscz' => 'Counter-Strike: Condition Zero',
            'csgo' => "Counter-Strike: Global Offensive",
            'css' => 'Counter-Strike: Source',
            'dayz' => 'DayZ Standalone',
            'dayzmod' => 'DayZ Mod',
            'discord' => 'Discord',
            'gmod' => "Garry's Mod",
            'grav' => 'GRAV Online',
            'gta5m' => 'GTA Five M',
            'gtan' => 'Grand Theft Auto Network',
            'hl2dm' => 'Half Life 2: Deathmatch',
            'hurtworld' => 'Hurtworld',
            'insurgency' => 'Insurgency',
            'jediacademy' => 'Star Wars Jedi Knight: Jedi Academy',
            'jedioutcast' => 'Star Wars Jedi Knight II: Jedi Outcast',
            'justcause2' => 'Just Cause 2 Multiplayer',
            'justcause3' => 'Just Cause 3',
            'killingfloor' => 'Killing Floor',
            'killingfloor2' => 'Killing Floor 2',
            'l4d' => 'Left 4 Dead',
            'l4d2' => 'Left 4 Dead 2',
            'minecraft' => "Minecraft",
            'mohaa' => 'Medal of honor: Allied Assault',
            'mta' => 'Multi Theft Auto',
            'mumble' => 'Mumble Server',
            'ns2' => 'Natural Selection 2',
            'quake2' => 'Quake 2 Server',
            'quake3' => 'Quake 3 Server',
            'quakelive' => 'Quake Live',
            'redorchestra2' => 'Red Orchestra 2',
            'rust' => 'Rust',
            'samp' => 'San Andreas Multiplayer',
            'sevendaystodie' => '7 Days to Die',
            'ship' => 'The Ship',
            'squad' => 'Squad',
            'starmade' => 'StarMade',
            'teamspeak3' => "Teamspeak 3",
            'teeworlds' => 'Teeworlds Server',
            'terraria' => 'Terraria',
            'tf2' => 'Team Fortress 2',
            'tibia' => 'Tibia',
            'tshock' => 'Tshock',
            'unreal2' => 'Unreal 2',
            'unturned' => 'Unturned',
            'ut3' => 'Unreal Tournament 3',
            'ut2004' => 'Unreal Tournament 2004',
            'ventrilo' => 'Ventrilo',
            'warsow' => 'Warsow',
            'won' => 'World Opponent Network',
        ], 'multiple' => false], null, null, null, 'aXenServerList_admin_table_mods_protocol'));

        if ($values = $form->values(true)) {
            $valuesArray = [
                'name' => $values['aXenServerList_admin_table_mods_name'],
                'icon' => (string) $values['aXenServerList_admin_table_mods_icon'],
            ];

            if ($values['aXenServerList_admin_table_mods_api']) {
                $valuesArray['api'] = 1;
                $valuesArray['protocol'] = 'api';
                $valuesArray['api_url'] = $values['aXenServerList_admin_table_mods_api_url'];
                $valuesArray['api_current_players'] = $values['aXenServerList_admin_table_mods_api_current_players'];
                $valuesArray['api_max_players'] = $values['aXenServerList_admin_table_mods_api_max_players'];
                $valuesArray['api_password'] = $values['aXenServerList_admin_table_mods_api_password'];
                $valuesArray['api_map'] = $values['aXenServerList_admin_table_mods_api_map'];
                $valuesArray['api_platform'] = $values['aXenServerList_admin_table_mods_api_platform'];
            } else {
                $valuesArray['api'] = 0;
                $valuesArray['protocol'] = $values['aXenServerList_admin_table_mods_protocol'];
            }

            /* Save into database */
            if ($item) {
                \IPS\Db::i()->update($this->table, $valuesArray, ['id=?', $id]);
            } else {
                \IPS\Db::i()->insert($this->table, $valuesArray);
            }

            \IPS\Output::i()->redirect(\IPS\Http\Url::internal('app=axenserverlist&module=servers&controller=mods'), 'saved');
        }

        /* Display */
        \IPS\Output::i()->title = $item ? $item['name'] : \IPS\Member::loggedIn()->language()->addToStack('menu__axenserverlist_servers_mods');
        \IPS\Output::i()->output = $form;
    }

    /**
     * Delete Mod
     *
     * @return    void
     */
    public function delete()
    {
        $id = \IPS\Request::i()->id;

        $item = [];
        try {
            $item = \IPS\Db::i()->select('*', $this->table, ['id=?', $id])->first();

            \IPS\Request::i()->confirmedDelete();
            \IPS\Db::i()->delete($this->table, ['id=?', $item['id']]);
            \IPS\File::get($this->table, $item['icon'])->delete();
        } catch (\UnderflowException$e) {
            \IPS\Output::i()->error('page_not_found', '(aXen) Advanced Server List/102', 404, '');
        }
    }
}
