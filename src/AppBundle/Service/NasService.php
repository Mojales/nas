<?php

namespace AppBundle\Service;


use AppBundle\Entity\TvShow;
use Symfony\Component\Filesystem\Filesystem;

class NasService
{

    const FODLDER_SEASON_NAME = 'Saison %s';

    protected $tvShowPath;
    protected $chown;

    public function __construct($tvShowPath, $chown)
    {
        $this->tvShowPath = $tvShowPath;
        $this->chown = $chown;
    }

    public function pathExist(TvShow $tvShow, $season)
    {
        $fileSystem = new Filesystem();
        $path = $this->tvShowPath .
            DIRECTORY_SEPARATOR .
            $tvShow->getNameFolder() .
            DIRECTORY_SEPARATOR .
            sprintf(self::FODLDER_SEASON_NAME, $season);
        if (!$fileSystem->exists($path)) {
            $fileSystem->mkdir($path);
            if ($this->chown) {
                $fileSystem->chown($path, 'admin:users');
            }
        }

        return $path;
    }

}