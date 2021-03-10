<?php
require_once str_replace('applications/axenserverlist/interface/gameqtest.php', '', str_replace('\\', '/', __FILE__)) . 'init.php';
require_once \IPS\Application::getRootPath() . '/applications/axenserverlist/interface/GameQ/Autoloader.php';


$servers = array([
  'type'    => 'csgo',
  'host'    => '137.74.5.141:27015'
]);

$gq = new \GameQ\GameQ();
$gq->setOption('write_wait', 10);
$gq->setOption('debug', true);

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
