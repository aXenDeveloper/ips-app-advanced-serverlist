<?php
/**
 * This file is part of app (aXen) Advanced Server List.
 */

namespace GameQ\Protocols;

/**
 * Class Codmw2
 *
 * @package GameQ\Protocols
 * @author  Austin Bischoff <austin@codebeard.com>
 */
class Codmw2 extends Source
{

    /**
     * String name of this protocol class
     *
     * @type string
     */
    protected $name = 'codmw2';

    /**
     * Longer string name of this protocol class
     *
     * @type string
     */
    protected $name_long = "Call of Duty: Modern Warfare 2";

    /**
     * query_port = client_port + 2
     *
     * @type int
     */
    protected $port_diff = 2;
}
