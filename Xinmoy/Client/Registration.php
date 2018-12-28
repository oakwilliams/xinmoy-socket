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


use Swoole\Process;


/**
 * Registration
 */
trait Registration {
    /*
     * Add registration process.
     */
    protected function _addRegistrationProcess() {
        if (empty($this->_server)) {
            throw new Exception('init failed');
        }

        $process = new Process([ $this, 'onRegistrationProcessAdd' ]);
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
            $client->connect();
        } catch (Exception $e) {
            handle_exception($e);
        }
    }
}
