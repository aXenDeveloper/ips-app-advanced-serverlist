<?php
/**
 * This file is part of app (aXen) Advanced Server List.
 */

namespace GameQ\Protocols;

/**
 * Class Codbo3
 *
 * @package GameQ\Protocols
 * @author  Austin Bischoff <austin@codebeard.com>
 */
class Codbo3 extends Source
{

    /**
     * String name of this protocol class
     *
     * @type string
     */
    protected $name = 'codbo3';

    /**
     * Longer string name of this protocol class
     *
     * @type string
     */
    protected $name_long = "Call of Duty: Black Ops 3";

    /**
     * query_port = client_port + 2
     *
     * @type int
     */
    protected $port_diff = 2;
}
