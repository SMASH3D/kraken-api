<?php

namespace KrakenApi\Command\Order;

use KrakenApi\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class CancelCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('order:cancel')
        ->setDescription('Cancel given order')
        ->addArgument(
            'txid', InputArgument::REQUIRED, 'Transaction id'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $result = $this->queryPrivate('CancelOrder', ['txid' => $input->getArgument('txid')]);
        if ($result['count'] > 0) {
            $io->success('Order '.$input->getArgument('txid').' successfully canceled');
        }
    }
}