<?php

namespace KrakenApi\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\TableSeparator;

class OrderBookCommand extends AbstractCommand
{
    private $_table;
    protected function configure()
    {
        $this->setName('orderbook:info')
            ->setDescription('Get market depth for a given pair')
            ->addArgument(
                'pair', InputArgument::REQUIRED, 'Pair'
            )
            ->addArgument('count', InputArgument::OPTIONAL, 'maximum number of asks/bids (optional)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->_table = new Table($output);

        $this->_table->setHeaders(['Pair', 'Type', 'Price', 'Volume', 'Timestamp']);

        $pair = $input->getArgument('pair');
        $arguments = ['pair' => $pair];
        if ($input->getArgument('count')) {
            $arguments['count'] = $input->getArgument('count');
        }
        $orderBook = $this->queryPublic('Depth', $arguments);

        foreach ($orderBook as $pair => $book) {
            if (isset($book['asks'])) {
                $asks = array_reverse($book['asks']);
                foreach ($asks as $id => $ask) {
                    $this->_addOrderRow($pair, 'sell', $ask);
                }
                $this->_table->addRow(new TableSeparator());
            }

            if (isset($book['bids'])) {
                foreach ($book['bids'] as $id => $bid) {
                    $this->_addOrderRow($pair, 'buy', $bid);
                }
            }
        }

        $this->_table->render();
    }

    private function _addOrderRow($pair, $type, $data) {
        $this->_table->addRow(
            [
                $pair,
                $type,
                $data[0],
                $data[1],
                date('d/m/Y - H:m:s', $data[2]),
            ]
        );
    }
}