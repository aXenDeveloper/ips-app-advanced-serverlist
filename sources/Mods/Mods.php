<?php

namespace IPS\axenserverlist;

class _Mods extends \IPS\Node\Model

{
    /**
     * @brief    Database Table
     */
    public static $databaseTable = 'axenserverlist_mods';

    /**
     * @brief    [Node] Order Database Column
     */
    public static $databaseColumnOrder = 'position';

    /**
     * Multiton Store
     */
    protected static $multitons = [];

    /**
     * Node Title
     */
    public static $nodeTitle = 'menu__axenserverlist_servers_mods';

    /**
     * [Node] Add/Edit Form
     *
     * @param    \IPS\Helpers\Form    $form    The form
     * @return    void
     */
    public function form(&$form)
    {
        \IPS\File::$safeFileExtensions[] = 'svg';

        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_name', $this->name, true));
        $form->add(new \IPS\Helpers\Form\Upload('aXenServerList_admin_table_mods_icon', $this->icon ? \IPS\File::get('axenserverlist_mods', $this->icon) : null, false, ['obscure' => true, 'allowedFileTypes' => array_merge(\IPS\Image::supportedExtensions(), ['svg']), 'checkImage' => true, 'storageExtension' => 'axenserverlist_mods'], function ($val) {
            if (!$val) {
                return;
            }

            /* Good luck with your fancy SVG XDD */
            $ext = mb_substr($val->originalFilename, (mb_strrpos($val->originalFilename, '.') + 1));
            if ($ext !== 'svg') {
                try
                {
                    $image = \IPS\Image::create($val->contents());
                } catch (\Exception$e) {
                    throw new \DomainException('achievements_bad_image');
                }
            }
        }));

        $form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_admin_table_mods_api', $this->api, false,
            [
                'togglesOff' => ['aXenServerList_admin_table_mods_protocol'],
                'togglesOn' => [
                    'aXenServerList_admin_table_mods_api_url',
                    'aXenServerList_admin_table_mods_api_status',
                    'aXenServerList_admin_table_mods_api_current_players',
                    'aXenServerList_admin_table_mods_api_max_players',
                    'aXenServerList_admin_table_mods_api_name',
                    'aXenServerList_admin_table_mods_api_password',
                    'aXenServerList_admin_table_mods_api_map',
                    'aXenServerList_admin_table_mods_api_platform',
                    'aXenServerList_admin_table_mods_api_connect_link',
                ],
            ]
        ));

        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_api_url', $this->api_url, true, [], null, null, null, 'aXenServerList_admin_table_mods_api_url'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_api_status', $this->api_status, true, [], null, null, null, 'aXenServerList_admin_table_mods_api_status'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_api_current_players', $this->api_current_players, true, [], null, null, null, 'aXenServerList_admin_table_mods_api_current_players'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_api_max_players', $this->api_max_players, true, [], null, null, null, 'aXenServerList_admin_table_mods_api_max_players'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_api_name', $this->api_name, false, [], null, null, null, 'aXenServerList_admin_table_mods_api_name'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_api_password', $this->api_password, false, [], null, null, null, 'aXenServerList_admin_table_mods_api_password'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_api_map', $this->api_map, false, [], null, null, null, 'aXenServerList_admin_table_mods_api_map'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_api_platform', $this->api_platform, false, [], null, null, null, 'aXenServerList_admin_table_mods_api_platform'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_admin_table_mods_api_connect_link', $this->api_connect_link, false, [], null, null, null, 'aXenServerList_admin_table_mods_api_connect_link'));

        $form->add(new \IPS\Helpers\Form\Select('aXenServerList_admin_table_mods_protocol', $this->protocol, true, ['options' => [
            'aa3' => "America's Army 3",
            'aapg' => "America's Army: Proving Grounds",
            'arkse' => 'ARK: Survival Evolved',
            'arma3' => 'Arma3',
            'api' => "Custom API",
            'bf2' => 'Battlefield 2',
            'bf3' => 'Battlefield 3',
            'bf4' => 'Battlefield 4',
            'bf1942' => 'Battlefield 1942',
            'bfbc2' => 'Battlefield Bad Company 2',
            'bfh' => 'Battlefield Hardline',
            'cod' => 'Call of Duty',
            'cod2' => 'Call of Duty 2',
            'cod4' => 'Call of Duty 4',
            'codbo3' => 'Call of Duty: Black Ops 3',
            'codmw2' => 'Call of Duty: Modern Warfare 2',
            'codmw3' => 'Call of Duty: Modern Warfare 3',
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
            'gta5m' => 'GTA: FiveM',
            'samp' => 'GTA: San Andreas Multiplayer',
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
            'sevendaystodie' => '7 Days to Die',
            'ship' => 'The Ship',
            'squad' => 'Squad',
            'starmade' => 'StarMade',
            'teamspeak3' => "Teamspeak 3",
            'teeworlds' => 'Teeworlds Server',
            'terraria' => 'Terraria',
            'tfclasic' => 'Team Fortress Classic',
            'tf2' => 'Team Fortress 2',
            'tf2clasic' => 'Team Fortress 2 Classic',
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
            if (mb_substr($k, 0, 32) === 'aXenServerList_admin_table_mods_') {
                $values[mb_substr($k, 32)] = $v;
            } else {
                $values[$k] = $v;
            }
        }

        // Change protocol to api when custom api is true
        $values['protocol'] = $values['api'] ? 'api' : $values['protocol'];

        if ($values['protocol'] == 'discord') {
            $values['api_url'] = 'https://discordapp.com/api/guilds/{ip}/widget.json';
            $values['api_status'] = 'name';
            $values['api_current_players'] = 'presence_count';
            $values['api_max_players'] = 'presence_count';
            $values['api_name'] = 'name';
            $values['api_connect_link'] = 'instant_invite';
        } elseif ($values['protocol'] != 'api') {
            $values['api_url'] = '';
            $values['api_status'] = '';
            $values['api_current_players'] = '';
            $values['api_max_players'] = '';
        }

        return $values;
    }

    /**
     * [Node] Get Title
     *
     * @return    string
     */
    protected function get__title()
    {
        return "#$this->id - $this->name";
    }

    /**
     * [Node] Get Icon for tree
     *
     * @note    Return the class for the icon (e.g. 'globe', the 'fa fa-' is added automatically so you do not need this here)
     * @return    mixed
     */
    protected function get__icon()
    {
        if ($this->icon) {
            return \IPS\File::get('axenserverlist_mods', $this->icon);
        }

        return \IPS\Theme::i()->resource('icons/unknown.png', 'axenserverlist', 'global');
    }

    /**
     * [Node] Get Node Description
     *
     * @return    string|null
     */
    protected function get__description()
    {
        if ($this->api) {
            return "$this->protocol - $this->api_url";
        }

        return $this->protocol;
    }

    /**
     * [ActiveRecord] Delete Record
     *
     * @return    void
     */
    public function delete()
    {
        \IPS\File::get('axenserverlist_mods', $this->icon)->delete();

        return parent::delete();
    }
}
