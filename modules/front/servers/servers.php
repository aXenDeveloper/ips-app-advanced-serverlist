<?php

namespace IPS\axenserverlist\modules\front\servers;

/* To prevent PHP errors (extending class does not exist) revealing path */

if (!\defined('\IPS\SUITE_UNIQUE_KEY')) {
    header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . ' 403 Forbidden');
    exit;
}

/**
 * servers
 */
class _servers extends \IPS\Dispatcher\Controller

{
    /**
     * Execute
     *
     * @return    void
     */
    public function execute()
    {
        parent::execute();
    }

    /**
     * ...
     *
     * @return    void
     */
    protected function manage()
    {
        \IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('module__axenserverlist_servers');
        \IPS\Output::i()->jsFiles = array_merge(\IPS\Output::i()->jsFiles, \IPS\Output::i()->js('front_axenserverlist.js', 'axenserverlist', 'front'));
        \IPS\Output::i()->output = \IPS\Theme::i()->getTemplate('pages', 'axenserverlist', 'front')->aXenServerListPage(\IPS\Application::load('axenserverlist')->getFullDataServers(), \IPS\Application::load('axenserverlist')->getMods(), 'horizontal');
    }

    // Create new methods with the same name as the 'do' parameter which should execute it
}
