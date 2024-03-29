<?php

/**
 * This file is part of GameQ.
 *
 * GameQ is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * GameQ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace GameQ\Protocols;

use GameQ\Buffer;
use GameQ\Protocol;
use GameQ\Result;

/**
 * GTA Five M Protocol Class
 *
 * Server base can be found at https://fivem.net/
 *
 * Based on code found at https://github.com/LiquidObsidian/fivereborn-query
 *
 * @author Austin Bischoff <austin@codebeard.com>
 */
class Gta5m extends Protocol
{

    /**
     * Array of packets we want to look up.
     * Each key should correspond to a defined method in this or a parent class
     *
     * @type array
     */
    protected $packets = [
        self::PACKET_STATUS => "\xFF\xFF\xFF\xFFgetinfo xxx",
    ];

    /**
     * Use the response flag to figure out what method to run
     *
     * @type array
     */
    protected $responses = [
        "\xFF\xFF\xFF\xFFinfoResponse" => "processStatus",
    ];

    /**
     * The query protocol used to make the call
     *
     * @type string
     */
    protected $protocol = 'gta5m';

    /**
     * String name of this protocol class
     *
     * @type string
     */
    protected $name = 'gta5m';

    /**
     * Longer string name of this protocol class
     *
     * @type string
     */
    protected $name_long = "GTA Five M";

    /**
     * Normalize settings for this protocol
     *
     * @type array
     */
    protected $normalize = [
        // General
        'general' => [
            // target       => source
            'gametype'   => 'gametype',
            'hostname'   => 'hostname',
            'mapname'    => 'mapname',
            'maxplayers' => 'sv_maxclients',
            'mod'        => 'gamename',
            'numplayers' => 'clients',
            'password'   => 'privateClients',
        ],
    ];

    /**
     * Process the response
     *
     * @return array
     * @throws \Exception
     */
    public function processResponse()
    {
        // In case it comes back as multiple packets (it shouldn't)
        $buffer = new Buffer(implode('', $this->packets_response));

        // Figure out what packet response this is for
        $response_type = $buffer->readString(PHP_EOL);

        // Figure out which packet response this is
        if (empty($response_type) || !array_key_exists($response_type, $this->responses)) {
            throw new \Exception(__METHOD__ . " response type '{$response_type}' is not valid");
        }

        // Offload the call
        $results = call_user_func_array([$this, $this->responses[$response_type]], [$buffer]);

        return $results;
    }

    /*
     * Internal methods
     */

    /**
     * Handle processing the status response
     *
     * @param Buffer $buffer
     *
     * @return array
     */
    protected function processStatus(Buffer $buffer)
    {
        // Set the result to a new result instance
        $result = new Result();

        // Lets peek and see if the data starts with a \
        if ($buffer->lookAhead(1) == '\\') {
            // Burn the first one
            $buffer->skip(1);
        }

        // Explode the data
        $data = explode('\\', $buffer->getBuffer());

        // No longer needed
        unset($buffer);

        $itemCount = count($data);

        // Now lets loop the array
        for ($x = 0; $x < $itemCount; $x += 2) {
            // Set some local vars
            $key = $data[$x];
            $val = $data[$x + 1];

            if (in_array($key, ['challenge'])) {
                continue; // skip
            }

            // Regular variable so just add the value.
            $result->add($key, $val);
        }

        /*var_dump($data);
        var_dump($result->fetch());

        exit;*/

        return $result->fetch();
    }
}
