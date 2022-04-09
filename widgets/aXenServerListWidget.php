<?php

/**
 * @brief        aXenServerListWidget Widget
 * @author        <a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright    (c) Invision Power Services, Inc.
 * @license        https://www.invisioncommunity.com/legal/standards/
 * @package        Invision Community
 * @subpackage    axenserverlist
 * @since        25 Feb 2021
 */

namespace IPS\axenserverlist\widgets;

/* To prevent PHP errors (extending class does not exist) revealing path */

if (!\defined('\IPS\SUITE_UNIQUE_KEY')) {
    header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . ' 403 Forbidden');
    exit;
}

/**
 * aXenServerListWidget Widget
 */
class _aXenServerListWidget extends \IPS\Widget

{
    /**
     * @brief    Widget Key
     */
    public $key = 'aXenServerListWidget';

    /**
     * @brief    App
     */
    public $app = 'axenserverlist';

    /**
     * @brief    Plugin
     */
    public $plugin = '';

    /**
     * @brief    Orientation
     */
    protected $orientation = null;

    /**
     * Initialise this widget
     *
     * @return void
     */
    public function init()
    {
        // Use this to perform any set up and to assign a template that is not in the following format:
        // $this->template( array( \IPS\Theme::i()->getTemplate( 'widgets', $this->app, 'front' ), $this->key ) );
        // If you are creating a plugin, uncomment this line:
        // $this->template( array( \IPS\Theme::i()->getTemplate( 'plugins', 'core', 'global' ), $this->key ) );
        // And then create your template at located at plugins/<your plugin>/dev/html/aXenServerListWidget.phtml
        $this->template(array(\IPS\Theme::i()->getTemplate('widgets', $this->app, 'front'), $this->key));
        parent::init();
    }

    /**
     * Specify widget configuration
     *
     * @param    null|\IPS\Helpers\Form    $form    Form object
     * @return    null|\IPS\Helpers\Form
     */
    public function configuration(&$form = null)
    {
        $form = parent::configuration($form);

        // $form->add( new \IPS\Helpers\Form\XXXX( .... ) );
        return $form;
    }

    /**
     * Ran before saving widget configuration
     *
     * @param    array    $values    Values from form
     * @return    array
     */
    public function preConfig($values)
    {
        return $values;
    }

    /**
     * Render a widget
     *
     * @return    string
     */
    public function render()
    {
        $select = \IPS\Db::i()->select(
            'axenserverlist_servers.id,
            axenserverlist_servers.name,
            axenserverlist_servers.ip,
            axenserverlist_servers.url_statistics,
            axenserverlist_servers.status,
            axenserverlist_servers.current_players,
            axenserverlist_servers.max_players,
            axenserverlist_servers.url_tv,
            axenserverlist_servers.url_vote,
            axenserverlist_servers.url_forum,
            axenserverlist_servers.map,
            axenserverlist_servers.new,
            axenserverlist_servers.url_connect,
            axenserverlist_servers.top_server,
            axenserverlist_servers.ip_custom,
            axenserverlist_servers.name_default,
            axenserverlist_servers.name_default_text,
            axenserverlist_servers.protocol,
            axenserverlist_servers.password,
            axenserverlist_servers.debug,
            axenserverlist_servers.debug_text_YesNo,
            axenserverlist_servers.custom_connect,
            axenserverlist_servers.custom_connect_link,
            axenserverlist_servers.owners,
            axenserverlist_servers.most_players,
            axenserverlist_mods.name as mod_name,
            axenserverlist_mods.icon as mod_icon,
            axenserverlist_mods.protocol as mod_protocol',
            'axenserverlist_servers', null, 'axenserverlist_servers.position ASC', null, null)->join('axenserverlist_mods', 'axenserverlist_mods.id=axenserverlist_servers.mod_id');

        return $this->output($select, $this->orientation, true);
    }
}
