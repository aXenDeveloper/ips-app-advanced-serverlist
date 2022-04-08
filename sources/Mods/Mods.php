<?php

namespace IPS\axenserverlist;

class _Mods extends \IPS\Node\Model

{
    /**
     * Multiton Store
     */
    protected static $multitons = [];

    /**
     * Node Title
     */
    public static $nodeTitle = 'menu__axenserverlist_servers_mods';

    /**
     * Database Table
     */
    public static $databaseTable = 'axenserverlist_mods';
    public static $databaseColumnOrder = 'position';

    /**
     * [Node] Add/Edit Form
     *
     * @param    \IPS\Helpers\Form    $form    The form
     * @return    void
     */
    public function form(&$form)
    {
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_settings_mods_name', $this->name, true));
        $form->add(new \IPS\Helpers\Form\Upload('aXenServerList_settings_mods_icon', $this->icon ? \IPS\File::get('axenserverlist_mods', $this->icon) : null, false, ['image' => true, 'storageExtension' => 'axenserverlist_mods']));
        $form->add(new \IPS\Helpers\Form\YesNo('aXenServerList_settings_mods_api', $this->api, false,
            [
                'togglesOff' => ['aXenServerList_settings_mods_protocol'],
                'togglesOn' => [
                    'aXenServerList_settings_mods_api_url',
                    'aXenServerList_settings_mods_api_current_players',
                    'aXenServerList_settings_mods_api_max_players',
                    'aXenServerList_settings_mods_api_password',
                    'aXenServerList_settings_mods_api_map',
                    'aXenServerList_settings_mods_api_platform',
                ],
            ]
        ));

        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_settings_mods_api_url', $this->api_url, true, [], null, null, null, 'aXenServerList_settings_mods_api_url'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_settings_mods_api_current_players', $this->api_current_players, false, [], null, null, null, 'aXenServerList_settings_mods_api_current_players'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_settings_mods_api_max_players', $this->api_max_players, false, [], null, null, null, 'aXenServerList_settings_mods_api_max_players'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_settings_mods_api_password', $this->api_password, false, [], null, null, null, 'aXenServerList_settings_mods_api_password'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_settings_mods_api_map', $this->api_map, false, [], null, null, null, 'aXenServerList_settings_mods_api_map'));
        $form->add(new \IPS\Helpers\Form\Text('aXenServerList_settings_mods_api_platform', $this->api_platform, false, [], null, null, null, 'aXenServerList_settings_mods_api_platform'));

        $form->add(new \IPS\Helpers\Form\Select('aXenServerList_settings_mods_protocol', $this->protocol, false, ['options' => [
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
        ], 'multiple' => false], null, null, null, 'aXenServerList_settings_mods_protocol'));
    }
}
