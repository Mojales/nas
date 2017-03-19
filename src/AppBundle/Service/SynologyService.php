<?php

namespace AppBundle\Service;

use Synology\Applications\DownloadStation;

class SynologyService
{

    protected $ip;
    protected $port;
    protected $protocol;
    protected $version;
    protected $login;
    protected $pass;
    protected $client;

    public function __construct($ip, $port, $protocol, $version, $login, $pass)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->protocol = $protocol;
        $this->version = $version;
        $this->login = $login;
        $this->pass = $pass;
    }

    public function createClient()
    {
        if (null === $this->client) {
            $this->client = new DownloadStation($this->ip, $this->port, $this->protocol, $this->version);
            $this->client->connect($this->login, $this->pass);
        }

        return $this->client;
    }

    public function addTorrent($path)
    {
        $client = $this->createClient();
        $client->addTask($path);
    }

}