<?php

namespace Remotelog;

use Mage;

class MagentoLogger extends Logger
{
    public function addLog($log)
    {
        if (true !== Mage::registry('remotelog_enabled')) {
            return;
        }
        $trace = $log[1];
        $trace = explode("\n", $trace);

        $mageLog = array(
            'message'     => $log[0],
            'stack_trace' => $trace,
            'type'        => 'CRITICAl',
            'code'        => 500,
            'url'         => Mage::helper('core/http')->getRequestUri(),
            'client_ip'   => Mage::helper('core/http')->getRemoteAddr(),
        );

        parent::addLog($mageLog);
    } 

}
