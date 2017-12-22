<?php

namespace KrakenApi\Command\Asset;

use KrakenApi\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class PairsCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('asset:pairs')
            ->setDescription('Retrieve asset pairs');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);

        $table->setHeaders(
            [
                'Name',
                'Base',
                'Quote',
            ]
        );

        foreach ($this->queryPublic('AssetPairs') as $data) {
            $table->addRow(
                [
                    $data['altname'],
                    $data['base'],
                    $data['quote'],
                ]
            );
        }

        $table->render();
    }
}