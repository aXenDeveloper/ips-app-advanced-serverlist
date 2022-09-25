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

    /**
     * Get data from GameQ lib
     *
     * @param object $server
     * @param boolean $debug
     * @return void
     */
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
        for ($i = 0; $i <= 3; $i++) {
            $results = null;

            try {
                $this->gq->clearServers();
                $this->gq->addServer($currentServer);
                $results = $this->gq->process();
            } catch (\Exception $e) {
                \IPS\Log::log($e, '(aXen) Advanced Server List - Server ID: ' . $server['id']);
            }

            if ($debug) {
                return $results;
            }

            $players = $this->searchArray($results, 'gq_numplayers');
            $maxPlayers = $this->searchArray($results, 'gq_maxplayers');
            $map = $this->searchArray($results, 'gq_mapname');

            $dataUpdate = [
                'status' => !!$this->searchArray($results, 'gq_online'),
                'current_players' => $players ? $players : 0,
                'max_players' => $maxPlayers ? $maxPlayers : 0,
                'map' => isset($map) ? $map : null,
                'url_connect' => $this->searchArray($results, 'gq_joinlink'),
                'protocol' => $this->searchArray($results, 'gq_protocol'),
                'password' => $this->searchArray($results, 'gq_password'),
            ];

            if (!!$this->searchArray($results, 'gq_online')) {
                $dataUpdate['name_default_text'] = $this->searchArray($results, 'gq_hostname');
            }

            if (!!$this->searchArray($results, 'gq_online') || $i == 3) {
                try {
                    \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['id=?', $server['id']]);
                } catch (\Exception $e) {
                    \IPS\Log::log($e, '(aXen) Advanced Server List - Server ID: ' . $server['id']);
                }
            }
        }
    }

    /**
     * Get data from Custom API
     *
     * @param object $server
     * @param boolean $debug
     * @return void
     */
    protected function getDataFromCustomApi($server, $debug)
    {
        $url = \IPS\Application::load('axenserverlist')->linkIpWithUrl($server['ip'], $server['mod_api_url']);
        if (!$url) {
            \IPS\Log::log('Invalid server URL!', '(aXen) Advanced Server List - Server ID: ' . $server['id']);
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

        try {
            \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['id=?', $server['id']]);
        } catch (\Exception $e) {
            \IPS\Log::log($e, '(aXen) Advanced Server List - Server ID: ' . $server['id']);
        }
    }

    /**
     * Data from GTA FiveM server
     *
     * @param object $server
     * @param boolean $debug
     * @return void
     */
    protected function getDataFromGtaFiveM($server, $debug)
    {
        $dataInfo = \IPS\Http\Url::external("http://{$server['ip']}/info.json")->request()->get()->decodeJson();
        $dataPlayers = \IPS\Http\Url::external("http://{$server['ip']}/players.json")->request()->get()->decodeJson();

        $countPlayers = 0;
        foreach ($dataPlayers as $_player) {
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

        if ($debug) {
            return $dataInfo;
        }

        try {
            \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['id=?', $server['id']]);
        } catch (\Exception $e) {
            \IPS\Log::log($e, '(aXen) Advanced Server List - Server ID: ' . $server['id']);
        }
    }
}
