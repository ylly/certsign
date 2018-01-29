<?php

namespace YllyCertiSign\Client\SMS;

use YllyCertiSign\Client\AbstractClient;

class SMSClient extends AbstractClient implements SMSClientInterface
{
    /** @var \SoapClient */
    private $client;

    private $environnement;

    private $endPoints = [
        'prod' => 'http://certisms.certeurope.fr/CertiSMS.php?wsdl',
        'test' => 'http://certisms-qualif.certeurope.fr/CertiSMS.php?wsdl'
    ];

    private $apiKey;

    private $proxy;

    public function __construct($environnement, $apiKey, $proxy)
    {
        $this->environnement = $environnement;
        if (!isset($this->endPoints[$this->environnement])) {
            throw new \Exception('Environnement not found');
        }

        $this->apiKey = $apiKey;
        $this->proxy = $proxy;
    }

    private function createClient()
    {
        $options = ['trace' => 1];
        $endPoint = $this->endPoints[$this->environnement];

        if ($this->proxy !== null) {
            $context = stream_context_create(['http' => ['proxy' => 'tcp://' . $this->proxy, 'request_fulluri' => true]]);
            $pHost = explode(':', $this->proxy)[0];
            $pPort = explode(':', $this->proxy)[1];

            $options = [
                'proxy_host'     => $pHost,
                'proxy_port'     => $pPort,
                'stream_context' => $context
            ];

            $tmpFile = tempnam(sys_get_temp_dir(), 'ylly_cert_sign');
            file_put_contents($tmpFile, file_get_contents($endPoint, null, $context));
            $endPoint = $tmpFile;
        }

        $this->client = new \SoapClient($endPoint, $options);

        if (isset($tmpFile)) {
            unlink($tmpFile);
        }
    }

    public function call($method, $args)
    {
        if (!$this->client instanceof \SoapClient) {
            $this->createClient();
        }

        $args = array_merge(['codeApplication' => $this->apiKey], $args);
        try {
            $response =  $this->client->__call($method, $args);
            $this->writeLog(self::INFO, $this->client->__getLastRequest());
            $this->writeLog(self::INFO, $this->client->__getLastResponse());

            return $response;
        } catch (\SoapFault $e) {
            $this->writeLog(self::INFO, $this->client->__getLastRequest());
            $this->writeLog(self::ERROR, $e->getMessage());

            throw $e;
        }
    }
}