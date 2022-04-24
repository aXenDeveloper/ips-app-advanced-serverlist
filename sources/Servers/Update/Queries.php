<?php

namespace IPS\axenserverlist\Servers\Update;

class _Queries
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

    protected function getDataFromGameQ($server, $debug)
    {
        $currentServer = [
            'id' => $server['id'],
            'type' => $server['mod_protocol'],
            'host' => $server['ip'],
        ];

        if ($server['query_port']) {
            $currentServer['options'] = [
                'query_port' => $server['query_port'],
            ];
        };

        // Try 3 times
        for ($i = 0; $i < 3; $i++) {
            $this->gq->clearServers();
            $this->gq->addServer($currentServer);
            $results = $this->gq->process();

            if ($debug) {
                return $results;
            }

            foreach ($results as $id => $data) {
                if ($data['gq_online'] == true) {
                    $dataUpdate = [
                        'status' => 1,
                        'current_players' => $data['gq_numplayers'] ? $data['gq_numplayers'] : 0,
                        'max_players' => $data['gq_maxplayers'] ? $data['gq_maxplayers'] : 0,
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
    }

    protected function getDataFromCustomApi($server, $debug)
    {
        $url = \IPS\Application::load('axenserverlist')->linkIpWithUrl($server['ip'], $server['mod_api_url']);
        if (!$url) {
            \IPS\Log::log('Invalid server URL!', '(aXen) Advanced Server List - Debug Server ID: ' . $server['id']);
            return;
        }

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

        if ($debug) {
            return $data;
        }

        \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['id=?', $server['id']]);
    }

    protected function getDataFromGtaFiveM($server, $debug)
    {
        $dataInfo = \IPS\Http\Url::external("http://{$server['ip']}/info.json")->request()->get()->decodeJson();
        $dataPlayers = \IPS\Http\Url::external("http://{$server['ip']}/players.json")->request()->get()->decodeJson();

        $countPlayers = 0;
        foreach ($dataPlayers as $player) {
            $countPlayers += 1;
        }

        $dataUpdate = [
            'status' => $this->searchArray($dataInfo, 'sv_maxClients') ? 1 : 0,
            'current_players' => $countPlayers,
            'max_players' => $this->searchArray($dataInfo, 'sv_maxClients') ?? 0,
            'protocol' => $this->searchArray($dataInfo, 'gamename'),
            'name_default_text' => $this->searchArray($dataInfo, 'sv_projectName'),
        ];

        // Update most players
        if ($countPlayers > $server['most_players']) {
            $dataUpdate['most_players'] = $countPlayers;
        }

        \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['id=?', $server['id']]);
        return $dataInfo;
    }
}
