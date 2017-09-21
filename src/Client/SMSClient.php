<?php

namespace YllyCertiSign\Client;

class SMSClient
{
    /** @var \SoapClient */
    private $client;

    private $environnement;

    private $endPoints = [
        'prod' => 'http://certisms.certeurope.fr/CertiSMS.php?wsdl',
        'test' => 'http://certisms-qualif.certeurope.fr/CertiSMS.php?wsdl'
    ];

    private $apiKey;

    public function __construct($environnement, $apiKey, $proxy)
    {
        $this->environnement = $environnement;
        if (!isset($this->endPoints[$this->environnement])) {
            throw new \Exception('Environnement not found');
        }

        $options = null;
        if ($proxy !== null) {
            $pHost = explode(':', $proxy)[0];
            $pPort = explode(':', $proxy)[0];
            $options = [
                'proxy_host'     => $pHost,
                'proxy_port'     => $pPort,
                'stream_context' => stream_context_create([
                    'proxy' => "tcp://$pHost:$pPort"
                ])
            ];
        }

        $this->client = new \SoapClient($this->endPoints[$this->environnement], $options);
        $this->apiKey = $apiKey;
    }

    public function call($method, $args)
    {
        $args = array_merge(['codeApplication' => $this->apiKey], $args);
        return $this->client->__call($method, $args);
    }
}