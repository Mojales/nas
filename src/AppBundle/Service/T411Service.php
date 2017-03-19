<?php

namespace AppBundle\Service;

use AppBundle\Entity\TvShow;
use GuzzleHttp\Client;
use Martial\T411\Api\Torrent\Torrent;

class T411Service
{

    protected $domain;
    protected $login;
    protected $pass;
    protected $token;
    protected $client;

    /**
     * T411Service constructor.
     * @param $domain
     * @param $login
     * @param $pass
     */
    public function __construct($domain, $login, $pass)
    {
        $this->domain = $domain;
        $this->login = $login;
        $this->pass = $pass;
    }

    /**
     * @return \Martial\T411\Api\Client
     */
    protected function createClient()
    {
        if (null === $this->client) {
            $httpClient = new Client([
                'base_uri' => $this->domain
            ]);

            $dataTransformer = new \Martial\T411\Api\Data\DataTransformer();
            $fs = new \Symfony\Component\Filesystem\Filesystem();
            $queryFactory = new \Martial\T411\Api\Search\QueryFactory();

            $config = [
                'torrent_files_path' => '/path/where/your/torrents/are/stored',
                'verify' => false
            ];

            $this->client = new \Martial\T411\Api\Client($httpClient, $dataTransformer, $fs, $queryFactory, $config);
        }
        return $this->client;
    }

    /**
     * @return \Martial\T411\Api\Authentication\TokenInterface
     */
    protected function getToken()
    {
        if (null === $this->token) {
            $client = $this->createClient();
            $this->token = $client->authenticate($this->login, $this->pass);
        }
        return $this->token;
    }

    public function searchNewTvShow(TvShow $tvShow)
    {
        $client = $this->createClient();
        $result = $client->search(
            $this->getToken(),
            [
                'terms' => $tvShow->getName(),
                'limit' => $this->getNbTvShow($tvShow),
                'category_id' => 433
            ]
        );

        $torrents = [];
        foreach ($result->getTorrents() as $item) {
            if ($item->isVerified() && preg_match('/s([0-9]*)e([0-9]*)/i', $item->getName(), $matches) === 1) {
                $season = (int)$matches[1];
                $episode = (int)$matches[2];

                $newEpisode = $tvShow->getSeason() == $season && $tvShow->getEpisode() < $episode;
                $newSeason = $tvShow->getSeason() < $season;

                if ($newEpisode || $newSeason) {
                    if (isset($torrents[$season][$episode])) {
                        if (strpos($item->getName(), '720p') !== false) {
                            $torrents[$season][$episode] = $item;
                        }
                    } else {
                        $torrents[$season][$episode] = $item;
                    }
                    ksort($torrents[$season]);
                }
            }
        }
        ksort($torrents);
        return $torrents;
    }

    protected function getNbTvShow(TvShow $tvShow)
    {
        $client = $this->createClient();
        $result = $client->search(
            $this->getToken(),
            [
                'terms' => $tvShow->getName(),
                'limit' => 1,
                'category_id' => 433
            ]
        );

        return (int)$result->getTotal();
    }

}