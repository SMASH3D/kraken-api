<?php

namespace KrakenApi\Command\Asset;

use KrakenApi\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class InfoCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('asset:info')
            ->setDescription('Retrieve assets info');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);

        $table->setHeaders(
            [
                'Name',
                'Decimal',
                'Display Decimal',
            ]
        );

        foreach ($this->queryPublic('Assets') as $data) {
            $table->addRow(
                [
                    $data['altname'],
                    $data['decimals'],
                    $data['display_decimals'],
                ]
            );
        }

        $table->render();
    }
}