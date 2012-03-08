<?php

namespace Remotelog;

class MagentoLogger extends Logger
{
    public function addLog($log)
    {
        $trace = $log[1];
        $trace = explode("\n", $trace);

        $mageLog = array(
            'message'     => $log[0],
            'stack_trace' => $trace,
            'type'        => 'critical'
        );

        parent::addLog($mageLog);
    } 

}
