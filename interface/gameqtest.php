<?php
require_once str_replace('applications/axenserverlist/interface/gameqtest.php', '', str_replace('\\', '/', __FILE__)) . 'init.php';
require_once \IPS\ROOT_PATH . '/applications/axenserverlist/interface/GameQ/Autoloader.php';


$servers = array([
  'type'    => 'csgo',
  'host'    => '91.224.117.161:27015',
]);

$gq = new \GameQ\GameQ();

foreach ($servers as $server) {
  try {
    $gq->clearServers();
    $gq->addServer($server);

    $gq->setOption('timeout', 3);

    $results = $gq->process();
    var_dump($results);
  } catch (\Exception $e) {
    echo $e . "\n";
    continue;
  }
}
