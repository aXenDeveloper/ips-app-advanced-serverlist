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

    $form->add(new \IPS\Helpers\Form\Select('axenserverlist_game', $this->game, TRUE, array('options' => array(
      'cs16' => "Counter-Strike 1.6",
      'csgo' => "Counter-Strike: Global Offensive",
      'minecraft' => "Minecraft",
      'teamspeak3' => "Teamspeak 3"
    ), 'multiple' => FALSE)));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_name', $this->name, TRUE));
    $form->add(new \IPS\Helpers\Form\YesNo('axenserverlist_name_default', $this->name_default, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_ip', $this->ip, TRUE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_ip_custom', $this->ip_custom, FALSE));
    $form->add(new \IPS\Helpers\Form\Member('axenserverlist_owners', $members, FALSE, array('multiple' => null)));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_statistics', $this->statistics, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_tv', $this->tv, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_vote', $this->vote, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_forum', $this->forum, FALSE));
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

    return $values;
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
