<?php

namespace IPS\axenserverlist;

class _Servers extends \IPS\Node\Model
{
  /**
   * Multiton Store
   */
  protected static $multitons = array();

  /**
   * Node Title
   */
  public static $nodeTitle = 'menu__axenserverlist_servers_servers';

  /**
   * Database Table
   */
  public static $databaseTable = 'axenserverlist_servers';
  public static $databasePrefix = 'axenserverlist_';
  public static $databaseColumnOrder = 'position';

  /**
   * Get URL
   *
   * @return	\IPS\Http\Url
   * @throws	\BadMethodCallException
   */
  public function url()
  {
    return;
  }

  /**
   * [Node] Add/Edit Form
   *
   * @param	\IPS\Helpers\Form	$form	The form
   * @return	void
   */
  public function form(&$form)
  {
    $members = array();
    if (!empty($this->owners)) {
      foreach (new \IPS\Patterns\ActiveRecordIterator(\IPS\Db::i()->select('*', 'core_members', array(\IPS\Db::i()->in('member_id', explode(",", $this->owners)))), 'IPS\Member') as $member) {
        $members[] = $member;
      }
    }

    $form->addTab('axenserverlist_tab_basic');
    $form->add(new \IPS\Helpers\Form\Select('axenserverlist_game', $this->game, TRUE, array('options' => array(
      'cs16' => "Counter-Strike 1.6",
      'csgo' => "Counter-Strike: Global Offensive",
      'discord' => 'Discord',
      'minecraft' => "Minecraft",
      'teamspeak3' => "Teamspeak 3"
    ), 'multiple' => FALSE)));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_name', $this->name, TRUE));
    $form->add(new \IPS\Helpers\Form\YesNo('axenserverlist_name_default', $this->name_default, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_ip', $this->ip, TRUE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_ip_custom', $this->ip_custom, FALSE));
    $form->add(new \IPS\Helpers\Form\Number('axenserverlist_query_port', $this->query_port == 0 ? NULL : $this->query_port, FALSE));
    $form->addTab('axenserverlist_tab_urls');
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_statistics', $this->statistics, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_tv', $this->tv, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_vote', $this->vote, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_forum', $this->forum, FALSE));

    $form->addTab('axenserverlist_tab_advanced');
    $form->add(new \IPS\Helpers\Form\Member('axenserverlist_owners', $members, FALSE, array('multiple' => null)));
    $form->add(new \IPS\Helpers\Form\YesNo('axenserverlist_new', $this->new, FALSE));
    $form->add(new \IPS\Helpers\Form\YesNo(
      'axenserverlist_top_server',
      $this->top_server,
      FALSE,
      array('togglesOn' => array(
        'axenserverlist_top_server_text'
      ))
    ));
    $form->add(new \IPS\Helpers\Form\Translatable('axenserverlist_top_server_text', NULL, FALSE, array('app' => 'app', 'key' => "axenserverlist_top_server_text_{$this->id}"), NULL, NULL, NULL, 'axenserverlist_top_server_text'));
  }

  /**
   * [Node] Format form values from add/edit form for save
   *
   * @param	array	$values	Values from the form
   * @return	array
   */
  public function formatFormValues($values)
  {
    // Save owners
    if (!empty($values['axenserverlist_owners'])) {
      $members = array();
      foreach ($values['axenserverlist_owners'] as $member) {
        $members[] = $member->member_id;
      }
      $values['axenserverlist_owners'] = implode(',', $members);
    } else {
      $values['axenserverlist_owners'] = NULL;
    }

    // Save Translatable
    if (isset($values['axenserverlist_top_server_text'])) {
      \IPS\Lang::saveCustom('axenserverlist', "axenserverlist_top_server_text_{$this->id}", $values['axenserverlist_top_server_text']);
    }

    require_once \IPS\ROOT_PATH . '/applications/axenserverlist/interface/GameQ/Autoloader.php';

    $gq = new \GameQ\GameQ();
    $gq->setOption('write_wait', 10);
    $gq->setOption('timeout', 3);



    // \IPS\Task::queue('axenserverlist', 'aXenServersQueryServers', array(), 1);
    return $values;
  }

  /**
   * [Node] Perform actions after saving the form
   *
   * @param	array	$values	Values from the form
   * @return	void
   */
  public function postSaveForm($values)
  {
    require_once \IPS\ROOT_PATH . '/applications/axenserverlist/interface/GameQ/Autoloader.php';

    $gq = new \GameQ\GameQ();
    $gq->setOption('write_wait', 10);
    $gq->setOption('timeout', 3);

    if ($values['axenserverlist_game'] != 'discord') {
      $server = [
        'id' => $this->id,
        'type' => $values['axenserverlist_game'],
        'host' => $values['axenserverlist_ip'],
      ];

      if ($values['axenserverlist_query_port']) {
        $server['options'] = [
          'query_port' => $values['axenserverlist_query_port']
        ];
      };

      try {
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

            if ($data['gq_numplayers'] > $this->most_players) {
              $dataUpdate['axenserverlist_most_players'] = $data['gq_numplayers'];
            }

            \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['axenserverlist_id=?', $this->id]);
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

            \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['axenserverlist_id=?', $this->id]);
          }
        }
      } catch (\Exception $e) {
        \IPS\Log::log($e, '(aXen) Server List - Server ID: ' . $server['id']);
      }
    } else {
      $url = "https://discordapp.com/api/guilds/" . $values['axenserverlist_ip'] . "/widget.json";
      $dataFromJSON = \IPS\Http\Url::external($url)->request()->get()->decodeJson();

      if (!$dataFromJSON['name']) {
        $dataUpdate = [
          'axenserverlist_status' => 0,
          'axenserverlist_current_players' => 0,
          'axenserverlist_max_players' => 0,
          'axenserverlist_game_long' => 'Discord',
          'axenserverlist_protocol' => 'discord'
        ];
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

      if ($dataFromJSON['presence_count'] > $this->most_players) {
        $dataUpdate['axenserverlist_most_players'] = $dataFromJSON['presence_count'];
      }

      \IPS\Db::i()->update('axenserverlist_servers', $dataUpdate, ['axenserverlist_id=?', $this->id]);
    }
  }

  /**
   * [Node] Get Title
   *
   * @return	string
   */
  protected function get__title()
  {
    $getName = $this->name_default && $this->name_default_text ? $this->name_default_text : $this->name;
    $getIP = $this->ip_custom ? $this->ip_custom : $this->ip;

    return $getName . ' - ' . $this->game . ' - ' . $getIP;
  }
}
