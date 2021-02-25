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
      'minecraftpe' => "MinecraftPE",
      'teamspeak3' => "Teamspeak 3"
    ), 'multiple' => FALSE)));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_name', $this->name, TRUE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_ip', $this->ip, TRUE));
    $form->add(new \IPS\Helpers\Form\Member('axenserverlist_owners', $members, FALSE, array('multiple' => null)));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_gt', $this->gt, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_tv', $this->tv, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_vote', $this->vote, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_topic', $this->topic, FALSE));
  }

  /**
   * [Node] Format form values from add/edit form for save
   *
   * @param	array	$values	Values from the form
   * @return	array
   */
  public function formatFormValues($values)
  {
    if (!empty($values['axenserverlist_owners'])) {
      $members = array();
      foreach ($values['axenserverlist_owners'] as $member) {
        $members[] = $member->member_id;
      }
      $values['axenserverlist_owners'] = implode(',', $members);
    } else {
      $values['axenserverlist_owners'] = NULL;
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
    return $this->name . ' - ' . $this->ip;
  }
}
