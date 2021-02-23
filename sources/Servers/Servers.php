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
  public static $databaseColumnOrder = 'axenserverlist_position';
  public static $databaseColumnId = 'axenserverlist_id';

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
    $form->add(new \IPS\Helpers\Form\Select('axenserverlist_type', $this->axenserverlist_type, TRUE, array('options' => array(0 => 'CS 1.6', 1 => 'CS:GO', 2 => 'TS3'), 'multiple' => FALSE)));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_name', $this->axenserverlist_name, TRUE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_ip', $this->axenserverlist_ip, TRUE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_owners', $this->axenserverlist_owners, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_gt', $this->axenserverlist_gt, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_tv', $this->axenserverlist_tv, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_vote', $this->axenserverlist_vote, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_topic', $this->axenserverlist_topic, FALSE));
  }

  /**
   * [Node] Get Title
   *
   * @return	string
   */
  protected function get__title()
  {
    return $this->axenserverlist_name;
  }
}
