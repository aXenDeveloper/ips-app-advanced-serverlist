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
        \IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('module__axenserverlist_servers');
        \IPS\Output::i()->output = \IPS\Theme::i()->getTemplate('pages', 'axenserverlist', 'front')->aXenServerListPage(\IPS\Application::load('axenserverlist')->getFullDataServers(), 'horizontal');
        parent::execute();
    }

    /**
     * ...
     *
     * @return    void
     */
    protected function manage()
    {
        // This is the default method if no 'do' parameter is specified
    }

    // Create new methods with the same name as the 'do' parameter which should execute it
}
