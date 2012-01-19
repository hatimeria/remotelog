<?php

namespace Remotelog;

class Logger
{
    /**
     * Host server
     * @var string
     */
    protected $server;
    /**
     * Route pattern
     *
     * @var string
     */
    protected $route;
    /**
     * Place string
     *
     * @var string
     */
    protected $place;

    public function __construct($server, $place, $route = '')
    {
        $this->server = $server;
        $this->place  = $place;
        $this->route  = $route;
    }

    public function addLog($log)
    {
        if ($log instanceof \Exception) {
            /* @var \Exception $e */
            $e = $log;

            $log = array(
                'message'     => $e->getMessage(),
                'stack_trace' => $e->getTrace(),
                'type'        => 'exception',
                'place'       => $this->place
            );
        } elseif (is_array($log)) {
            $log = array_merge(array(
                'place' => $this->place,
            ),$log);
        } else {
            return;
        }

        $post = json_encode($log);

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $this->server . $this->route);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);

        $result = curl_exec($ch);

        curl_close($ch);
    }

}