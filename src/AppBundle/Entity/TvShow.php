<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class TvShow
 *
 * @ORM\Entity()
 * @ORM\Table()
 */
class TvShow
{

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $nameFolder;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $season;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $episode;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * @param int $season
     * @return $this
     */
    public function setSeason($season)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * @return int
     */
    public function getEpisode()
    {
        return $this->episode;
    }

    /**
     * @param int $episode
     * @return $this
     */
    public function setEpisode($episode)
    {
        $this->episode = $episode;
        return $this;
    }

    /**
     * @return string
     */
    public function getNameFolder()
    {
        return $this->nameFolder;
    }

    /**
     * @param string $nameFolder
     */
    public function setNameFolder($nameFolder)
    {
        $this->nameFolder = $nameFolder;
    }

}