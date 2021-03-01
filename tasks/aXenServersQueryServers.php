<?php

/**
 * @brief		aXenServersQueryServers Task
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community
 * @subpackage	axenserverlist
 * @since		24 Feb 2021
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
	 * @return	mixed	Message to log or NULL
	 * @throws	\IPS\Task\Exception
	 */
	public function execute()
	{
		require_once \IPS\ROOT_PATH . '/applications/axenserverlist/interface/GameQ/Autoloader.php';

		$getServers = \IPS\Db::i()->select('*', 'axenserverlist_servers', NULL, 'axenserverlist_position DESC');

		$gq = new \GameQ\GameQ();
		$gq->setOption('write_wait', 10);
		$gq->setOption('timeout', 3);

		foreach ($getServers as $row) {
			$server = array(
				'id' => $row['axenserverlist_id'],
				'type' => $row['axenserverlist_game'],
				'host' => $row['axenserverlist_ip']
			);

			try {
				// Try 3 times
				for ($i = 1; $i <= 3; $i++) {
					$gq->clearServers();
					$gq->addServer($server);

					$results = $gq->process();

					foreach ($results as $id => $data) {
						if ($data['gq_online'] == true) {
							$dataUpdate = [
								'axenserverlist_status' => 1,
								'axenserverlist_current_players' => $data['gq_numplayers'],
								'axenserverlist_max_players' => $data['max_players'],
								'axenserverlist_name_default' => $data['gq_hostname'],
								'axenserverlist_map' => $data['gq_mapname'],
								'axenserverlist_game_long' => $data['gq_name'],
								'axenserverlist_connect_link' => $data['gq_joinlink']
							];

							\IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['axenserverlist_id=?', $row['axenserverlist_id']]);
							break;
						} else {
							$dataUpdate = [
								'axenserverlist_status' => 0,
								'axenserverlist_current_players' => 0,
								'axenserverlist_max_players' => 0,
								'axenserverlist_map' => null,
								'axenserverlist_game_long' => $data['gq_name'],
								'axenserverlist_connect_link' => $data['gq_joinlink']
							];

							if ($i == 3) {
								\IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['axenserverlist_id=?', $row['axenserverlist_id']]);
							}
						}
					}
				}
			} catch (\Exception $e) {
				\IPS\Log::log($e, '(aXen) Server List - Server ID: ' . $server['id']);
			}
		}

		return NULL;
	}

	/**
	 * Cleanup
	 *
	 * If your task takes longer than 15 minutes to run, this method
	 * will be called before execute(). Use it to clean up anything which
	 * may not have been done
	 *
	 * @return	void
	 */
	public function cleanup()
	{
	}
}
