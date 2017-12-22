#!/usr/bin/env php
<?php

define('BASE_DIR', __DIR__);

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application('Kraken API');

$application->add(new \KrakenApi\Command\Order\ListCommand());
$application->add(new \KrakenApi\Command\Order\CancelCommand());
$application->add(new \KrakenApi\Command\Order\AddCommand());
$application->add(new \KrakenApi\Command\BalanceCommand());
$application->add(new \KrakenApi\Command\TickerCommand());
$application->add(new \KrakenApi\Command\Asset\InfoCommand());
$application->add(new \KrakenApi\Command\Asset\PairsCommand());

$application->run();