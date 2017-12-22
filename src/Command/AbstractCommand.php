<?php

namespace KrakenApi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Yaml\Yaml;
use Payward\KrakenAPI;

class AbstractCommand extends Command
{
    protected function getClient()
    {
        $data = Yaml::parseFile('config.yml');
        return new KrakenAPI($data['key'], $data['secret']);
    }

    protected function queryPrivate($method, array $request = [], $retry = true)
    {
        $result = false;
        while (!$result) {
            try {
                $result = $this->getClient()->QueryPrivate($method, $request);
            } catch (\Payward\KrakenAPIException $e) {
                if (!$retry) {
                    throw $e;
                }
                if ($e->getMessage() == 'JSON decode error') {
                    $result = false;
                }
            }
        }

        if (!empty($result['error'])) {
            throw new \Exception(implode("\n", $result['error']));
        }

        return $result['result'];
    }

    protected function queryPublic($method, array $request = [], $retry = true)
    {
        $result = false;
        while (!$result) {
            try {
                $result = $this->getClient()->QueryPublic($method, $request);
            } catch (\Payward\KrakenAPIException $e) {
                if (!$retry) {
                    throw $e;
                }
                if ($e->getMessage() == 'JSON decode error') {
                    $result = false;
                }
            }
        }

        if (!empty($result['error'])) {
            throw new \Exception(implode("\n", $result['error']));
        }

        return $result['result'];
    }
}