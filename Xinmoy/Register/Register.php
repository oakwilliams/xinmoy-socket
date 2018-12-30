<?php
/*
 * Register
 *
 * @author Oak Williams <oakwilliams@gmail.com>
 * @date   05/02/2018
 *
 * @copyright 2018 Xinmoy, Inc. All Rights Reserved.
 */


namespace Xinmoy\Register;


use Exception;

use Xinmoy\Swoole\Server;


/**
 * Register
 */
class Register extends Server {
    /**
     * onSendToGroup
     *
     * @param Server $server     server
     * @param int    $fd         fd
     * @param int    $reactor_id reactor id
     * @param array  $data       data
     */
    public function onSendToGroup($server, $fd, $reactor_id, $data) {
        if (empty($data['group']) || empty($data['type'])) {
            throw new Exception('wrong group/type');
        }

        $this->sendToAll('sendtogroup', $data);
    }


    /**
     * onSendToAll
     *
     * @param Server $server     server
     * @param int    $fd         fd
     * @param int    $reactor_id reactor id
     * @param array  $data       data
     */
    public function onSendToAll($server, $fd, $reactor_id, $data) {
        if (empty($data['type'])) {
            throw new Exception('wrong type');
        }

        $this->sendToAll('sendtoall', $data);
    }
}
