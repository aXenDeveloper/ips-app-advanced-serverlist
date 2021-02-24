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
    $members = array();
    if (!empty($this->axenserverlist_owners)) {
      foreach (new \IPS\Patterns\ActiveRecordIterator(\IPS\Db::i()->select('*', 'core_members', array(\IPS\Db::i()->in('member_id', explode(",", $this->axenserverlist_owners)))), 'IPS\Member') as $member) {
        $members[] = $member;
      }
    }

    $form->add(new \IPS\Helpers\Form\Select('axenserverlist_type', $this->axenserverlist_type, TRUE, array('options' => array(
      'aa3' => "America's Army 3",
      'aapg' => "America's Army: Proving Grounds",
      'arkse' => "ARK: Survival Evolved",
      'arma' => "ArmA Armed Assault",
      'arma3' => "Arma3",
      'armedassault2oa' => "Armed Assault 2: Operation Arrowhead",
      'ase' => "All-Seeing Eye",
      'atlas' => "Atlas",
      'batt1944' => "Battalion 1944",
      'bf2' => "Battlefield 2",
      'bf4' => "Battlefield 4"
    ), 'multiple' => FALSE)));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_name', $this->axenserverlist_name, TRUE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_ip', $this->axenserverlist_ip, TRUE));
    // $form->add(new \IPS\Helpers\Form\Member('axenserverlist_owners', \IPS\Member::load($this->axenserverlist_owners), FALSE, array('multiple' => 1)));
    $form->add(new \IPS\Helpers\Form\Member('axenserverlist_owners', $members, FALSE, array('multiple' => null)));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_gt', $this->axenserverlist_gt, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_tv', $this->axenserverlist_tv, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_vote', $this->axenserverlist_vote, FALSE));
    $form->add(new \IPS\Helpers\Form\Text('axenserverlist_topic', $this->axenserverlist_topic, FALSE));
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
      $values['axenserverlist_owners'] = 'all';
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
    return $this->axenserverlist_name . ' - ' . $this->axenserverlist_ip;
  }
}
