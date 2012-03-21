<?php

namespace Remotelog\Handler;

use Monolog\Logger,
    Monolog\Handler\AbstractProcessingHandler;

use Remotelog\Logger as Remotelog;

class RemotelogHandler extends AbstractProcessingHandler
{
    protected $logger;
    protected $enabled;

    public function __construct($server, $place, $route, $enabled, $level = Logger::ERROR)
    {
        $this->logger = new Remotelog($server, $place, $route);
        $this->enabled = $enabled;

        parent::__construct($level);
    }

    public function write(array $record)
    {
        $log = array(
            'type' => $record['level_name'],
            'message' => $record['message']
        );
        
        foreach(array('url', 'client_ip', 'code', 'parameters') as $key) {
            if(isset($record[$key])) {
                $log[$key] = $record[$key];
            }
        }

        $this->addLog($log);
    }

    public function addLog($log)
    {
        if($this->enabled) {
            $this->logger->addLog($log);
        }
    }

}