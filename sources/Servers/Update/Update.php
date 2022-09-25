<?php

namespace IPS\axenserverlist\Servers;

class _Update extends \IPS\axenserverlist\Servers\Update\Queries

{
    public function server($server, $api = null, $debug = false)
    {
        try {
            if ($api) {
                $this->getDataFromCustomApi($server, $debug);
                return;
            }

            if ($server['mod_protocol'] == 'gta5m') {
                $this->getDataFromGtaFiveM($server, $debug);
                return;
            }

            $this->getDataFromGameQ($server, $debug);
        } catch (\Exception $e) {
            \IPS\Log::log($e, '(aXen) Advanced Server List - Server ID: ' . $server['id']);
        }
    }
}
