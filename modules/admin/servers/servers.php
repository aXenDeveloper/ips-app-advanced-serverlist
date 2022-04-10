<?php

namespace IPS\axenserverlist\modules\admin\servers;

/* To prevent PHP errors (extending class does not exist) revealing path */

if (!\defined('\IPS\SUITE_UNIQUE_KEY')) {
    header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . ' 403 Forbidden');
    exit;
}

/**
 * servers
 */
class _servers extends \IPS\Node\Controller

{
    /**
     * @brief    Has been CSRF-protected
     */
    public static $csrfProtected = true;

    /**
     * Node Class
     */
    protected $nodeClass = 'IPS\axenserverlist\Servers';

    /**
     * Get Root Buttons
     *
     * @return    array
     */
    public function _getRootButtons()
    {
        \IPS\Output::i()->sidebar['actions']['add'] = [
            'icon' => 'plus',
            'title' => 'menu__axenserverlist_servers_servers',
            'link' => $this->url->setQueryString('do', 'form'),
            'data' => ['ipsDialog' => '', 'ipsDialog-title' => \IPS\Member::loggedIn()->language()->addToStack('menu__axenserverlist_servers_servers')],
            'primary' => true,
        ];

        \IPS\Output::i()->sidebar['actions']['refresh'] = array(
            'icon' => 'refresh',
            'link' => \IPS\Http\Url::internal('app=axenserverlist&module=servers&controller=servers&do=refresh'),
            'title' => 'menu__axenserverlist_servers_servers_refresh',
        );

        \IPS\Output::i()->sidebar['actions']['test'] = array(
            'icon' => 'test',
            'link' => \IPS\Http\Url::internal('app=axenserverlist&module=servers&controller=servers&do=test'),
            'title' => 'test',
        );
    }

    /**
     * Refresh server list
     *
     * @return    void
     */
    protected function refresh()
    {
        try {
            $task = \IPS\Task::constructFromData(\IPS\Db::i()->select('*', 'core_tasks', array('`app`=? AND `key`=?', 'axenserverlist', 'aXenServersQueryServers'))->first());

            if ($task->running) {
                $task->unlock();
            }

            $output = $task->run();
            if ($output !== null) {
                throw \Exception;
            }

            \IPS\Output::i()->redirect(\IPS\Http\Url::internal('app=axenserverlist&module=servers&controller=servers'), 'aXenServerList_popup_refresh');
        } catch (\Exception$e) {
            \IPS\Log::debug($e, '(aXen) Advanced Server List - Update data servers');
        }
    }

    /**
     * Refresh server list
     *
     * @return    void
     */
    protected function test()
    {
        $update = new \IPS\axenserverlist\Servers\Update;

        $servers = [];
        try
        {
            $servers = \IPS\Application::load('axenserverlist')->getFullDataServersTask();
        } catch (\Exception$e) {
            \IPS\Log::log($e, '(aXen) Advanced Server List - Task');
        }

        foreach ($servers as $server) {
            if ($server['mod_protocol'] == 'api' || $server['mod_protocol'] == 'discord') {
                $update->server($server, true);
            } else {
                $update->server($server);
            }

        }
    }

    /**
     * Execute
     *
     * @return    void
     */
    public function execute()
    {
        \IPS\Dispatcher::i()->checkAcpPermission('servers_manage');
        parent::execute();
    }
}
