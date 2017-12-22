<?php

namespace KrakenApi\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;

class TickerCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('ticker:info')
            ->setDescription('Get ticker info for a given pair')
            ->addArgument(
                'pair', InputArgument::REQUIRED, 'Pair'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);

        $table->setHeaders(['Last', 'High', 'Low', 'Weighted']);

        foreach ($this->queryPublic('Ticker', ['pair' => $input->getArgument('pair')]) as $data) {
            $table->addRow(
                [
                    rtrim($data['c'][0], 0),
                    rtrim($data['h'][0], 0),
                    rtrim($data['l'][0], 0),
                    rtrim($data['p'][0], 0),
                ]
            );
        }

        $table->render();
    }
}