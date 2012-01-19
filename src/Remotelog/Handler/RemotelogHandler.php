<?php

namespace Remotelog\Handler;

use Monolog\Logger,
    Monolog\Handler\AbstractProcessingHandler;

use Remotelog\Logger as Remotelog;

class RemotelogHandler extends AbstractProcessingHandler
{
    protected $logger;

    public function __construct($server, $place, $route, $level = Logger::ERROR)
    {
        $this->logger = new Remotelog($server, $place, $route);

        parent::__construct($level);
    }

    public function write(array $record)
    {
        $log = array(
            'type' => $record['level_name'],
            'message' => $record['formatted']
        );

        $this->addLog($log);
    }

    public function addLog($log)
    {
        $this->logger->addLog($log);
    }

}