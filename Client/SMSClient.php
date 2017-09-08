<?php

namespace Ylly\CertiSign\Client;

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

    public function __construct($environnement, $apiKey)
    {
        $this->environnement = $environnement;
        if (!isset($this->endPoints[$this->environnement])) {
            throw new \Exception('Environnement not found');
        }

        $this->client = new \SoapClient($this->endPoints[$this->environnement]);
        $this->apiKey = $apiKey;
    }

    public function call($method, $args)
    {
        $args = array_merge(['codeApplication' => $this->apiKey], $args);
        return $this->client->__call($method, $args);
    }
}