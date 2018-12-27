<?php
/*
 * Server
 *
 * @author Oak Williams <oakwilliams@gmail.com>
 * @date   06/26/2018
 *
 * @copyright 2018 Xinmoy, Inc. All Rights Reserved.
 */


namespace Xinmoy\Server;


use Xinmoy\Swoole\Server as SwooleServer;
use Xinmoy\Client\Register;
use Xinmoy\Client\MySQL;
use Xinmoy\Client\Redis;


/**
 * Server
 */
class Server extends SwooleServer {
    use Register, MySQL, Redis;


    /**
     * Start.
     */
    public function start() {
        $this->_addMySQLConnections();
        $this->_addRedisConnections();

        parent::start();
    }
}
