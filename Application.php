<?php

/**
 * @brief        (aXen) Advanced Server List Application Class
 * @author        <a href='https://axendev.net/'>aXenDev</a>
 * @copyright    (c) 2021 aXenDev
 * @package        Invision Community
 * @subpackage    (aXen) Advanced Server List
 * @since        23 Feb 2021
 * @version
 */

namespace IPS\axenserverlist;

/**
 * (aXen) Advanced Server List Application Class
 */
class _Application extends \IPS\Application

{
    /**
     * [Node] Get Icon for tree
     *
     * @note    Return the class for the icon (e.g. 'globe')
     * @return    string|null
     */
    protected function get__icon()
    {
        return 'server';
    }

    /**
     * Get servers data and mods from database
     *
     * @return array
     */
    public function getFullDataServers()
    {
        return \IPS\Db::i()->select(
            'axenserverlist_servers.id,
            axenserverlist_servers.name,
            axenserverlist_servers.ip,
            axenserverlist_servers.url_statistics,
            axenserverlist_servers.status,
            axenserverlist_servers.current_players,
            axenserverlist_servers.max_players,
            axenserverlist_servers.url_tv,
            axenserverlist_servers.url_vote,
            axenserverlist_servers.url_forum,
            axenserverlist_servers.map,
            axenserverlist_servers.new,
            axenserverlist_servers.url_connect,
            axenserverlist_servers.top_server,
            axenserverlist_servers.ip_custom,
            axenserverlist_servers.name_default,
            axenserverlist_servers.name_default_text,
            axenserverlist_servers.protocol,
            axenserverlist_servers.password,
            axenserverlist_servers.debug,
            axenserverlist_servers.debug_text_YesNo,
            axenserverlist_servers.custom_connect,
            axenserverlist_servers.custom_connect_link,
            axenserverlist_servers.owners,
            axenserverlist_servers.most_players,
            axenserverlist_mods.name as mod_name,
            axenserverlist_mods.icon as mod_icon,
            axenserverlist_mods.protocol as mod_protocol',
            'axenserverlist_servers', null, 'axenserverlist_servers.position ASC', null, null)->join('axenserverlist_mods', 'axenserverlist_mods.id=axenserverlist_servers.mod_id');
    }

    /**
     * Get servers data and mods from database for task
     *
     * @return array
     */
    public function getFullDataServersTask()
    {
        return \IPS\Db::i()->select(
            'axenserverlist_servers.id,
            axenserverlist_servers.ip,
            axenserverlist_servers.query_port,
            axenserverlist_servers.most_players,
            axenserverlist_mods.protocol as mod_protocol',
            'axenserverlist_servers', null, 'axenserverlist_servers.position ASC', null, null)->join('axenserverlist_mods', 'axenserverlist_mods.id=axenserverlist_servers.mod_id');
    }
}
