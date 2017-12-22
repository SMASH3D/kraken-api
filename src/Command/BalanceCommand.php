<?php

namespace KrakenApi\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class BalanceCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('balance:info')
            ->setDescription('Get asset names and balance amount');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);

        $table->setHeaders(['Crypto', 'Amount']);

        foreach ($this->queryPrivate('Balance') as $crypto => $amount) {
            $table->addRow([$crypto, $amount]);
        }

        $table->render();
    }
}