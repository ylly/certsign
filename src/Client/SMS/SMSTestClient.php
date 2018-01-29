<?php

namespace YllyCertiSign\Client\SMS;

use YllyCertiSign\Client\AbstractClient;

class SMSTestClient extends AbstractClient implements SMSClientInterface
{
    public function call($method, $args)
    {
        if (in_array($method, ['AddAcces', 'CheckAcces'])) {
            return (object)['error' => 0, 'errormsg' => 'OK'];
        } else {
            throw new \Exception('Call on undefined API method');
        }
    }
}
