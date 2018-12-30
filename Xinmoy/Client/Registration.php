<?php
/*
 * Registration
 *
 * @author Oak Williams <oakwilliams@gmail.com>
 * @date   09/01/2018
 *
 * @copyright 2018 Xinmoy, Inc. All Rights Reserved.
 */


namespace Xinmoy\Client;


use Exception;

use Swoole\Process as SwooleProcess;
use Swoole\Event;

use Xinmoy\Swoole\Process;


/**
 * Registration
 */
trait Registration {
    use Process;


    /*
     * Add registration process.
     */
    protected function _addRegistrationProcess() {
        if (empty($this->_server)) {
            throw new Exception('init failed');
        }

        $process = new SwooleProcess([ $this, 'onRegistrationProcessAdd' ]);
        $this->setProcess($process);
        $this->_server->addProcess($process);
    }


    /**
     * onRegistrationProcessAdd
     *
     * @param Process $process process
     */
    public function onRegistrationProcessAdd($process) {
        try {
            if (empty($this->_registerHost) || ($this->_registerPort < 0)) {
                throw new Exception('wrong register host/port');
            }

            $client = new RegistrationClient($this->_registerHost, $this->_registerPort);
            $client->setProcess($process);
            $client->connect();
        } catch (Exception $e) {
            handle_exception($e);
        }
    }


    /**
     * onWorkerStart
     *
     * @param Server $server    server
     * @param int    $worker_id worker id
     */
    public function onWorkerStart($server, $worker_id) {
        try {
            parent::onWorkerStart($server, $worker_id);

            if ($worker_id != 0) {
                return;
            }

            if (empty($this->_process)) {
                throw new Exception('process init failed');
            }

            Event::add($this->_process->pipe, [ $this, 'onRead' ]);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }


    /**
     * Send to group.
     *
     * @param string $group group
     * @param string $type  type
     * @param array  $data  optional, data
     */
    public function sendToGroup($group, $type, $data = null) {
        if (empty($group) || empty($type)) {
            throw new Exception('wrong group/type');
        }

        $this->write('sendtogroupbyregister', [
            'group' => $group,
            'type' => $type,
            'data' => $data
        ]);
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

        if (!isset($data['data'])) {
            $data['data'] = null;
        }

        parent::sendToGroup($data['group'], $data['type'], $data['data']);
    }
}
