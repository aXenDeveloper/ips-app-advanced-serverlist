<?php

namespace IPS\axenserverlist\Servers;

class _Update
{
    /**
     * GameQ init
     */
    protected $gq;

    public function __construct()
    {
        require_once \IPS\Application::getRootPath() . '/applications/axenserverlist/sources/GameQ/Autoloader.php';

        $this->gq = new \GameQ\GameQ();
        $this->gq->setOption('write_wait', 10);
    }

    public function server($server, $api = false)
    {
        if ($api) {
            $url = \IPS\Application::load('axenserverlist')->linkIpWithUrl($server['ip'], $server['mod_api_url']);
            $data = \IPS\Http\Url::external($url)->request()->get()->decodeJson();

            $dataUpdate = [
                'status' => $this->searchArray($data, $server['mod_api_status']) ? 1 : 0,
                'current_players' => $this->searchArray($data, $server['mod_api_current_players']) ?? 0,
                'max_players' => $this->searchArray($data, $server['mod_api_max_players']) ?? 0,
                'protocol' => $this->searchArray($data, 'folder'),
            ];

            // Name
            if ($server['mod_api_name']) {
                $dataUpdate['name_default_text'] = $this->searchArray($data, $server['mod_api_name']);
            }

            // Password
            if ($server['mod_api_password']) {
                $dataUpdate['password'] = $this->searchArray($data, $server['mod_api_password']);
            }

            // Map
            if ($server['mod_api_map']) {
                $dataUpdate['map'] = $this->searchArray($data, $server['mod_api_map']);
            }

            // Protocol
            if ($server['mod_api_platform']) {
                $dataUpdate['protocol'] = $this->searchArray($data, $server['mod_api_platform']);
            }

            // Connect link
            if ($server['mod_api_connect_link']) {
                $dataUpdate['url_connect'] = $this->searchArray($data, $server['mod_api_connect_link']);
            }

            // Update most players
            if ($this->searchArray($data, $server['mod_api_current_players']) > $server['most_players']) {
                $dataUpdate['most_players'] = $this->searchArray($data, $server['mod_api_current_players']);
            }

            \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['id=?', $server['id']]);

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
                $this->gq->clearServers();
                $this->gq->addServer($currentServer);

                foreach ($this->gq->process() as $id => $data) {
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

                        // Update most players
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

    /**
     * Search value by key
     *
     * @param array $array Array
     * @param string|number $key Key
     * @return string|number|boolean
     */
    protected function searchArray($array, $key)
    {
        while ($array) {
            if (isset($array[$key])) {
                return $array[$key];
            }

            $segment = array_shift($array);
            if (\is_array($segment)) {
                if ($return = $this->searchArray($segment, $key)) {
                    return $return;
                }
            }
        }

        return false;
    }

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
}
