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
		require_once \IPS\Application::getRootPath() . '/applications/axenserverlist/interface/GameQ/Autoloader.php';

		$getServers = \IPS\Db::i()->select('*', 'axenserverlist_servers', NULL, 'axenserverlist_position DESC');

		$gq = new \GameQ\GameQ();
		// $gq->setOption('write_wait', 10);
		// $gq->setOption('timeout', 3);

		foreach ($getServers as $row) {
			if ($row['axenserverlist_game'] != 'discord') {
				$server = [
					'id' => $row['axenserverlist_id'],
					'type' => $row['axenserverlist_game'],
					'host' => $row['axenserverlist_ip'],
				];

				if ($row['axenserverlist_query_port']) {
					$server['options'] = [
						'query_port' => $row['axenserverlist_query_port']
					];
				};

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
									'axenserverlist_max_players' => $data['gq_maxplayers'],
									'axenserverlist_name_default_text' => $data['gq_hostname'],
									'axenserverlist_map' => isset($data['gq_mapname']) ? $data['gq_mapname'] : NULL,
									'axenserverlist_game_long' => $data['gq_name'],
									'axenserverlist_connect_link' => $data['gq_joinlink'],
									'axenserverlist_protocol' => $data['gq_protocol'],
									'axenserverlist_password' => $data['gq_password']
								];

								if ($data['gq_numplayers'] > $row['axenserverlist_most_players']) {
									$dataUpdate['axenserverlist_most_players'] = $data['gq_numplayers'];
								}

								\IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['axenserverlist_id=?', $row['axenserverlist_id']]);
								continue 2;
							} else {
								$dataUpdate = [
									'axenserverlist_status' => 0,
									'axenserverlist_current_players' => 0,
									'axenserverlist_max_players' => 0,
									'axenserverlist_map' => NULL,
									'axenserverlist_game_long' => $data['gq_name'],
									'axenserverlist_connect_link' => $data['gq_joinlink'],
									'axenserverlist_protocol' => $data['gq_protocol'],
									'axenserverlist_password' => $data['gq_password']
								];

								if ($i == 3) {
									\IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['axenserverlist_id=?', $row['axenserverlist_id']]);
								}
							}
						}
					}
				} catch (\Exception $e) {
					\IPS\Log::log($e, '(aXen) Advanced Server List - Server ID: ' . $server['id']);
				}
			} else {
				$url = "https://discordapp.com/api/guilds/" . $row['axenserverlist_ip'] . "/widget.json";
				$dataFromJSON = \IPS\Http\Url::external($url)->request()->get()->decodeJson();

				if (!$dataFromJSON['name']) {
					$dataUpdate = [
						'axenserverlist_status' => 0,
						'axenserverlist_current_players' => 0,
						'axenserverlist_max_players' => 0,
						'axenserverlist_game_long' => 'Discord',
						'axenserverlist_protocol' => 'discord'
					];

					\IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['axenserverlist_id=?', $row['axenserverlist_id']]);
					continue;
				}

				$dataUpdate = [
					'axenserverlist_status' => 1,
					'axenserverlist_current_players' => $dataFromJSON['presence_count'],
					'axenserverlist_max_players' => $dataFromJSON['presence_count'],
					'axenserverlist_name_default_text' => $dataFromJSON['name'],
					'axenserverlist_game_long' => 'Discord',
					'axenserverlist_connect_link' => $dataFromJSON['instant_invite'],
					'axenserverlist_protocol' => 'discord'
				];

				if ($dataFromJSON['presence_count'] > $row['axenserverlist_most_players']) {
					$dataUpdate['axenserverlist_most_players'] = $dataFromJSON['presence_count'];
				}

				\IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['axenserverlist_id=?', $row['axenserverlist_id']]);
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
