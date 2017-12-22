<?php

namespace KrakenApi\Command\Order;

use KrakenApi\Command\AbstractCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class ListCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('order:list')
        ->setDescription('List open orders')
        ->setHelp('This command provide a summary of open orders')
        ->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Limit number of result', 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);

        $table->setHeaders(
            [
                'Order',
                'Type',
                'Pair',
                'Price',
                'Volume',
                'Cost',
            ]
        );

        $limit = $input->getOption('limit');

        $iteration = 0;
        $orders = $this->queryPrivate('OpenOrders', ['trades' => false]);
        $table->addRow([new TableCell('Open orders', ['colspan' => 6])])
            ->addRow(new TableSeparator());
        foreach ($orders['open'] as $id => $data) {
            $table->addRow(
                [
                    $id,
                    $data['descr']['type'].'/'.$data['descr']['ordertype'],
                    $data['descr']['pair'],
                    $data['descr']['price'],
                    $data['vol'],
                    $data['descr']['price']*$data['vol'],
                ]
            );

            if (++$iteration == $limit) {
                break;
            }
        }

        $iteration = 0;
        $orders = $this->queryPrivate('ClosedOrders', ['trades' => false]);
        $table->addRow(new TableSeparator())
            ->addRow([new TableCell('Closed orders', ['colspan' => 6])])
            ->addRow(new TableSeparator());
        foreach ($orders['closed'] as $id => $data) {
            $table->addRow(
                [
                    $id,
                    $data['descr']['type'].'/'.$data['descr']['ordertype'],
                    $data['descr']['pair'],
                    $data['descr']['price'],
                    $data['vol'],
                    $data['descr']['price']*$data['vol'],
                ]
            );

            if (++$iteration == $limit) {
                break;
            }
        }

        $table->render();
    }
}