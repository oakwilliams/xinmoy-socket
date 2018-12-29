<?php
/*
 * Registration Client
 *
 * @author Oak Williams <oakwilliams@gmail.com>
 * @date   06/27/2018
 *
 * @copyright 2018 Xinmoy, Inc. All Rights Reserved.
 */


namespace Xinmoy\Client;


use Exception;

use Swoole\Event;

use Xinmoy\Swoole\AsyncClient;
use Xinmoy\Swoole\Process;


/**
 * Registration Client
 */
class RegistrationClient extends AsyncClient {
    use Process;


    /**
     * onConnect
     *
     * @param Client $client client
     */
    public function onConnect($client) {
        try {
            parent::onConnect($client);

            if (empty($this->_process)) {
                throw new Exception('process init failed');
            }

            Event::add($this->_process->pipe, [ $this, 'onRead' ]);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }


    /**
     * onSendToGroup
     *
     * @param Client $client client
     * @param array  $data   optional, data
     */
    public function onSendToGroup($client, $data = null) {
        if (empty($data['group']) || empty($data['type'])) {
            throw new Exception('wrong group/type');
        }

        $this->write('sendtogroupbyregister', $data);
    }


    /**
     * onSendToGroupByRegister
     *
     * @param array $data optional, data
     */
    public function onSendToGroupByRegister($data = null) {
        if (empty($data['group']) || empty($data['type'])) {
            throw new Exception('wrong group/type');
        }

        $this->send('sendtogroup', $data);
    }
}
