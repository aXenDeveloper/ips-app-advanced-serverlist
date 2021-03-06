<?php

/**
 * @brief		(aXen) Advanced Server List Application Class
 * @author		<a href='https://axendev.net/'>aXenDev</a>
 * @copyright	(c) 2021 aXenDev
 * @package		Invision Community
 * @subpackage	(aXen) Advanced Server List
 * @since		23 Feb 2021
 * @version		
 */

namespace IPS\axenserverlist;

/**
 * (aXen) Advanced Server List Application Class
 */
class _Application extends \IPS\Application
{
  /**
   * [Node] Get Icon for tree
   *
   * @note	Return the class for the icon (e.g. 'globe')
   * @return	string|null
   */
  protected function get__icon()
  {
    return 'server';
  }
}
