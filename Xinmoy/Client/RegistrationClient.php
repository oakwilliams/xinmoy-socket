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
     * @param array  $data   data
     */
    public function onSendToGroup($client, $data) {
        if (empty($data['group']) || empty($data['type'])) {
            throw new Exception('wrong group/type');
        }

        $this->write('sendtogroupbyregister', $data);
    }


    /**
     * onSendToAll
     *
     * @param Client $client client
     * @param array  $data   data
     */
    public function onSendToAll($client, $data) {
        if (empty($data['type'])) {
            throw new Exception('wrong type');
        }

        $this->write('sendtoallbyregister', $data);
    }


    /**
     * onSendToGroupByRegister
     *
     * @param array $data data
     */
    public function onSendToGroupByRegister($data) {
        if (empty($data['group']) || empty($data['type'])) {
            throw new Exception('wrong group/type');
        }

        $this->send('sendtogroup', $data);
    }


    /**
     * onSendToAllByRegister
     *
     * @param array $data data
     */
    public function onSendToAllByRegister($data) {
        if (empty($data['type'])) {
            throw new Exception('wrong type');
        }

        $this->send('sendtoall', $data);
    }
}
