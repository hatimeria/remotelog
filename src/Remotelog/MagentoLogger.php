<?php

namespace Remotelog;

use Mage;

class MagentoLogger extends Logger
{
    public function addExceptionLog($log)
    {
        $trace = $log[1];
        $trace = explode("\n", $trace);

        $mageLog = array(
            'message'     => $log[0],
            'stack_trace' => $trace,
            'type'        => 'CRITICAL',
            'code'        => 500,
        );

        self::addLog($mageLog);
    }

    public function addLog($log)
    {
        if (true !== Mage::registry('remotelog_enabled')) {
            return;
        }

        $mageLog = array();

        if('cli' !== php_sapi_name()) {
            $mageLog = array_merge(array(
                'url'       => Mage::helper('core/http')->getRequestUri(),
                'client_ip' => Mage::helper('core/http')->getRemoteAddr(),
            ), $log);
            if (!isset($mageLog['parameters']) || !is_array($mageLog['parameters'])) {
                $mageLog['parameters'] = array();
	        }
            if(!empty($_POST)) {
                $mageLog['parameters']['post'] = $_POST;
            }
            if(!empty($_FILES)) {
                $mageLog['parameters']['files'] = $_FILES;
            }
            if(!empty($_SESSION)) {
                $mageLog['parameters']['session'] = $_SESSION;
            }
            if(!empty($_COOKIE)) {
                $mageLog['parameters']['cookie'] = $_COOKIE;
            }
            if(isset($_SERVER['HTTP_USER_AGENT'])) {
                $mageLog['parameters']['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            }
            if(isset($_SERVER['HTTP_REFERER'])) {
                $mageLog['parameters']['referer'] = $_SERVER['HTTP_REFERER'];
            }
        }

        parent::addLog($mageLog);
    }

}
