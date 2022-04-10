<?php

/**
 * @brief        aXenServersQueryServers Task
 * @author        <a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright    (c) Invision Power Services, Inc.
 * @license        https://www.invisioncommunity.com/legal/standards/
 * @package        Invision Community
 * @subpackage    axenserverlist
 * @since        24 Feb 2021
 */

namespace IPS\axenserverlist\tasks;

/* To prevent PHP errors (extending class does not exist) revealing path */

if (!\defined('\IPS\SUITE_UNIQUE_KEY')) {
    header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . ' 403 Forbidden');
    exit;
}

/**
 * aXenServersQueryServers Task
 */
class _aXenServersQueryServers extends \IPS\Task

{
    /**
     * Execute
     *
     * If ran successfully, should return anything worth logging. Only log something
     * worth mentioning (don't log "task ran successfully"). Return NULL (actual NULL, not '' or 0) to not log (which will be most cases).
     * If an error occurs which means the task could not finish running, throw an \IPS\Task\Exception - do not log an error as a normal log.
     * Tasks should execute within the time of a normal HTTP request.
     *
     * @return    mixed    Message to log or NULL
     * @throws    \IPS\Task\Exception
     */
    public function execute()
    {
        require_once \IPS\Application::getRootPath() . '/applications/axenserverlist/sources/GameQ/Autoloader.php';

        $servers = \IPS\Application::load('axenserverlist')->getFullDataServersTask();

        $gq = new \GameQ\GameQ();
        $gq->setOption('write_wait', 10);

        foreach ($servers as $server) {
            if ($server['mod_protocol'] == 'api' || $server['mod_protocol'] == 'discord') {
                \IPS\Log::log($e, '(aXen) Advanced Server List - Server ID API: ' . $server['id']);
                return;
            }

            try {
                $currentServer = [
                    'id' => $server['id'],
                    'type' => $server['mod_protocol'],
                    'host' => $server['ip'],
                ];

                if ($server['query_port']) {
                    $server['options'] = [
                        'query_port' => $server['query_port'],
                    ];
                };

                // Try 3 times
                for ($i = 0; $i < 3; $i++) {
                    $gq->clearServers();
                    $gq->addServer($currentServer);

                    foreach ($gq->process() as $id => $data) {
                        if ($data['gq_online'] == true) {
                            $dataUpdate = [
                                'status' => 1,
                                'current_players' => $data['gq_numplayers'] ? $data['gq_numplayers'] : 0,
                                'max_players' => $data['gq_maxplayers'] ? $data['gq_maxplayers'] : $data['gq_numplayers'],
                                'name_default_text' => $data['gq_hostname'],
                                'map' => isset($data['gq_mapname']) ? $data['gq_mapname'] : null,
                                'url_connect' => $data['gq_joinlink'],
                                'protocol' => $data['gq_protocol'],
                                'password' => $data['gq_password'],
                            ];

                            if ($data['gq_numplayers'] > $server['most_players']) {
                                $dataUpdate['most_players'] = $data['gq_numplayers'];
                            }

                            \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['id=?', $server['id']]);
                            continue 2;
                        } else {
                            $dataUpdate = [
                                'status' => 0,
                                'current_players' => 0,
                                'max_players' => 0,
                                'map' => null,
                                'url_connect' => $data['gq_joinlink'],
                                'protocol' => $data['gq_protocol'],
                            ];

                            if ($i == 3) {
                                \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['id=?', $server['id']]);
                            }
                        }
                    }
                }
            } catch (\Exception$e) {
                \IPS\Log::log($e, '(aXen) Advanced Server List - Server ID: ' . $server['id']);
            }
        }

        // foreach ($getServers as $row) {
        //     if ($row['game'] != 'discord') {
        //         $server = [
        //             'id' => $row['id'],
        //             'type' => $row['game'],
        //             'host' => $row['ip'],
        //         ];

        //         if ($row['query_port']) {
        //             $server['options'] = [
        //                 'query_port' => $row['query_port'],
        //             ];
        //         };

        //         try {
        //             // Try 3 times
        //             for ($i = 1; $i <= 3; $i++) {
        //                 $gq->clearServers();
        //                 $gq->addServer($server);

        //                 $results = $gq->process();

        //                 foreach ($results as $id => $data) {
        //                     if ($data['gq_online'] == true) {
        //                         $dataUpdate = [
        //                             'status' => 1,
        //                             'current_players' => $data['gq_numplayers'],
        //                             'max_players' => $data['gq_maxplayers'] ? $data['gq_maxplayers'] : $data['gq_numplayers'],
        //                             'name_default_text' => $data['gq_hostname'],
        //                             'map' => isset($data['gq_mapname']) ? $data['gq_mapname'] : null,
        //                             'game_long' => $data['gq_name'],
        //                             'url_connect' => $data['gq_joinlink'],
        //                             'protocol' => $data['gq_protocol'],
        //                             'password' => $data['gq_password'],
        //                         ];

        //                         if ($data['gq_numplayers'] > $row['most_players']) {
        //                             $dataUpdate['most_players'] = $data['gq_numplayers'];
        //                         }

        //                         \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['id=?', $row['id']]);
        //                         continue 2;
        //                     } else {
        //                         $dataUpdate = [
        //                             'status' => 0,
        //                             'current_players' => 0,
        //                             'max_players' => 0,
        //                             'map' => null,
        //                             'game_long' => $data['gq_name'],
        //                             'url_connect' => $data['gq_joinlink'],
        //                             'protocol' => $data['gq_protocol'],
        //                             'password' => $data['gq_password'],
        //                         ];

        //                         if ($i == 3) {
        //                             \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['id=?', $row['id']]);
        //                         }
        //                     }
        //                 }
        //             }
        //         } catch (\Exception$e) {
        //             \IPS\Log::log($e, '(aXen) Advanced Server List - Server ID: ' . $server['id']);
        //         }
        //     } else {
        //         try {
        //             $url = "https://discordapp.com/api/guilds/" . $row['ip'] . "/widget.json";
        //             $dataFromJSON = \IPS\Http\Url::external($url)->request()->get()->decodeJson();

        //             if (!$dataFromJSON['name']) {
        //                 $dataUpdate = [
        //                     'status' => 0,
        //                     'current_players' => 0,
        //                     'max_players' => 0,
        //                     'game_long' => 'Discord',
        //                     'protocol' => 'discord',
        //                 ];

        //                 \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['id=?', $row['id']]);
        //                 continue;
        //             }

        //             $dataUpdate = [
        //                 'status' => 1,
        //                 'current_players' => $dataFromJSON['presence_count'],
        //                 'max_players' => $dataFromJSON['presence_count'],
        //                 'name_default_text' => $dataFromJSON['name'],
        //                 'game_long' => 'Discord',
        //                 'url_connect' => $dataFromJSON['instant_invite'],
        //                 'protocol' => 'discord',
        //             ];

        //             if ($dataFromJSON['presence_count'] > $row['most_players']) {
        //                 $dataUpdate['most_players'] = $dataFromJSON['presence_count'];
        //             }

        //             \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['id=?', $row['id']]);
        //         } catch (\Exception$e) {
        //             \IPS\Log::log($e, '(aXen) Advanced Server List - Server ID: ' . $server['id']);
        //         }
        //     }
        // }

        return null;
    }

    /**
     * Cleanup
     *
     * If your task takes longer than 15 minutes to run, this method
     * will be called before execute(). Use it to clean up anything which
     * may not have been done
     *
     * @return    void
     */
    public function cleanup()
    {
    }
}
