<?php

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TorrentCommand extends ContainerAwareCommand
{

    const COMMAND_NAME = 'AppBundle:Torrent';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Download torrent');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tvShows = $this->getContainer()
            ->get('doctrine')
            ->getRepository('AppBundle:TvShow')
            ->findAll();

        foreach ($tvShows as $tvShow) {
            $results = $this->getContainer()->get('app.t411')->searchNewTvShow($tvShow);
            foreach ($results as $season => $episodes) {
                foreach ($episodes as $episode => $torrent) {
                    var_dump($season, $episode, $torrent->getName());
                }
            }
        }
    }

}