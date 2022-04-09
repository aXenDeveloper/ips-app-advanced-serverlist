<?php
/**
 * @brief        2.0.0 Upgrade Code
 * @author        <a href='https://www.invisioncommunity.com'>aXenDev</a>
 * @copyright    aXenDev
 * @license        https://www.invisioncommunity.com/legal/standards/
 * @package        Invision Community
 * @subpackage    Advanced Server List
 * @since        09 Apr 2022
 */

namespace IPS\axenserverlist\setup\upg_10016;

/* To prevent PHP errors (extending class does not exist) revealing path */
if (!\defined('\IPS\SUITE_UNIQUE_KEY')) {
    header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . ' 403 Forbidden');
    exit;
}

/**
 * 2.0.0 Upgrade Code
 */
class _Upgrade
{
    /**
     * Import mods form servers database
     *
     * @return    boolean
     */
    public function step1()
    {
        $servers = \IPS\Db::i()->select('*', 'axenserverlist_servers');

        $mods = [];
        foreach ($servers as $server) {
            $mods[$server['game']] = $server['game_long'];
        }

        foreach ($mods as $protocol => $title) {
            \IPS\Db::i()->insert('axenserverlist_mods', [
                'name' => $title,
                'protocol' => $protocol,
            ]);
        }

        $mods = \IPS\Db::i()->select('*', 'axenserverlist_mods');
        foreach ($servers as $server) {
            foreach ($mods as $mod) {
                if ($server['game'] === $mod['protocol']) {
                    \IPS\Db::i()->update('axenserverlist_servers', ['mod_id' => $mod['id']], ['id=?', $server['id']]);
                }
            }
        }

        \IPS\Db::i()->dropColumn('axenserverlist_servers', 'game');
        \IPS\Db::i()->dropColumn('axenserverlist_servers', 'game_long');

        return true;
    }
}
