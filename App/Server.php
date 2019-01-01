<?php
/*
 * Server
 *
 * @author Oak Williams <oakwilliams@gmail.com>
 * @date   12/29/2018
 *
 * @copyright 2018 Xinmoy, Inc. All Rights Reserved.
 */


namespace App;


use Xinmoy\Server\Server as XinmoyServer;


/**
 * Server
 */
class Server extends XinmoyServer {
    /**
     * onTest
     *
     * @param Server $server     server
     * @param int    $fd         fd
     * @param int    $reactor_id reactor id
     * @param array  $data       data
     */
    public function onTest($server, $fd, $reactor_id, $data) {
        $this->send($fd, 'test');
    }
}
