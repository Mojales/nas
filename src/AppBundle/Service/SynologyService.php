<?php

namespace AppBundle\Service;

use AppBundle\Model\Torrent;
use GuzzleHttp\Client;

class SynologyService
{

    protected $ip;
    protected $port;
    protected $protocol;
    protected $version;
    protected $login;
    protected $pass;
    protected $sid;

    public function __construct($ip, $port, $protocol, $version, $login, $pass)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->protocol = $protocol;
        $this->version = $version;
        $this->login = $login;
        $this->pass = $pass;
    }

    public function getSid()
    {
        if (null === $this->sid) {
            $response = file_get_contents($this->protocol . '://' . $this->ip . '/webapi/auth.cgi?/webapi/auth.cgi?api=SYNO.API.Auth&version=2&method=login&account='.$this->login.'&passwd='.$this->pass.'&session=DownloadStation&format=sid');
            $data = \GuzzleHttp\json_decode($response, true);
            if ($data['success']) {
                $this->sid = $data['data']['sid'];
            }
        }

        return $this->sid;
    }

    public function addTorrent($pathTorrent, $destination)
    {
        $client = new Client();

        $torrent = new Torrent($pathTorrent);

        $response = $client->request(
            'POST',
            $this->protocol . '://' . $this->ip . '/webapi/DownloadStation/task.cgi',
            [
                'api' => 'SYNO.DownloadStation.Task',
                'version' => $this->version,
                'method' => 'create',
                '_sid' => $this->getSid(),
                'destination' => $destination,
                'uri' => rawurlencode($destination),
            ]
        );

        var_dump($response->getStatusCode(), $response->getBody()->getContents());
        die;

    }

}