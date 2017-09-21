<?php

namespace YllyCertiSign\Client;

use Buzz\Browser;
use Buzz\Client\Curl;

class SignClient
{
    private $client;

    private $environnement;

    private $endPoints = [
        'prod' => 'https://sign.certeurope.fr/',
        'test' => 'https://sign-sandbox.certeurope.fr/'
    ];

    public function __construct($environnement, $certPath, $certPassword, $proxy)
    {
        $this->environnement = $environnement;
        if (!isset($this->endPoints[$this->environnement])) {
            throw new \Exception('Environnement not found');
        }

        $curl = new Curl();
        $curl->setVerifyPeer(false);
        $curl->setOption(CURLOPT_SSLCERT, $certPath);
        $curl->setOption(CURLOPT_SSLCERTPASSWD, $certPassword);

        if ($proxy !== null) {
            $curl->setOption(CURLOPT_PROXY, explode(':', $proxy)[0]);
            $curl->setOption(CURLOPT_PROXYPORT, explode(':', $proxy)[1]);
        }

        $this->client = new Browser($curl);
    }

    private function getEndpoint()
    {
        return $this->endPoints[$this->environnement];
    }

    public function get($url)
    {
        $response = $this->client->get($this->getEndpoint() . $url);
        return json_decode($response->getContent());
    }

    public function post($url, $content = [])
    {
        $response = $this->client->post($this->getEndpoint() . $url, ['Content-Type' => 'application/json'], json_encode($content));
        return json_decode($response->getContent());
    }
}