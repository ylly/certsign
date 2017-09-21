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
        $options = [];
        $endPoint = $this->endPoints[$this->environnement];

        if ($this->proxy !== null) {
            $context = stream_context_create(['http' => ['proxy' => 'tcp://' . $this->proxy]]);
            $pHost = explode(':', $this->proxy)[0];
            $pPort = explode(':', $this->proxy)[0];

            $options = [
                'proxy_host'     => $pHost,
                'proxy_port'     => $pPort,
                'stream_context' => $context
            ];

            $tmpFile = tempnam(sys_get_temp_dir(), 'ylly_cert_sign');
            file_put_contents($endPoint, file_get_contents($endPoint, null, $context));
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
        return $this->client->__call($method, $args);
    }
}