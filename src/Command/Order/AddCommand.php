<?php

namespace KrakenApi\Command\Order;

use KrakenApi\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('order:add')
        ->setDescription('Add standard order')
        ->addArgument('pair', InputArgument::REQUIRED, 'Asset pair')
        ->addArgument('type', InputArgument::REQUIRED, 'Primary type (buy, sell)')
        ->addArgument('ordertype', InputArgument::REQUIRED, 'Secondary type (market, limit)')
        ->addArgument('price', InputArgument::REQUIRED, 'Price')
        ->addArgument('volume', InputArgument::REQUIRED, 'Volume')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $result = false;
        try {
            $result = $this->placeOrder(
                $input->getArgument('pair'),
                $input->getArgument('type'),
                $input->getArgument('ordertype'),
                $input->getArgument('price'),
                $input->getArgument('volume')
            );
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            if ($io->confirm('An error occured, would you like to retry ?', true)) {
                $result = $this->placeOrder(
                    $input->getArgument('pair'),
                    $input->getArgument('type'),
                    $input->getArgument('ordertype'),
                    $input->getArgument('price'),
                    $input->getArgument('volume')
                );
            }
        }

        if ($result) {
            $io->success(
                [
                    $result['descr']['order'],
                    'txid => '.implode(', ', $result['txid']),
                ]
            );
        }
    }

    protected function placeOrder($pair, $type, $ordertype, $price, $volume)
    {
        return $this->queryPrivate(
            'AddOrder',
            [
                'pair' => $pair,
                'type' => $type,
                'ordertype' => $ordertype,
                'price' => $price,
                'volume' => $volume,
            ],
            false
        );
    }
}