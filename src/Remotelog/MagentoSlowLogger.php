<?php

namespace Remotelog;

use Mage;

class MagentoSlowLogger
{
    /**
     * Empty request limit in miliseconds
     *
     * @var int
     */
    protected $emptyRequestLimit;
    /**
     * Post request limit in miliseconds
     *
     * @var int
     */
    protected $postRequestLimit;
    /**
     * @var MagentoLogger
     */
    protected $logger;
    /**
     * @var int
     */
    protected $start;

    public function __construct(MagentoLogger $logger, $autostart = true, $emptyRequestLimit = 2000, $postRequestLimit = 4000)
    {
        $this->logger            = $logger;
        $this->emptyRequestLimit = $emptyRequestLimit;
        $this->postRequestLimit  = $postRequestLimit;

        if ($autostart) {
            $this->start = microtime(true);
        }
    }

    public function start()
    {
        if (null !== $this->start) {
            return;
        }

        $this->start = microtime(true);
    }

    public function stop()
    {
        $stop = microtime(true);
        if (null === $this->start) {
            return;
        }
        // we don't measure post with files
        if (!empty($_FILES)) {
            return;
        }
        if (!empty($_POST)) {
            $limit = $this->postRequestLimit;
        } else {
            $limit = $this->emptyRequestLimit;
        }

        $limit = $limit;
        $result = $stop * 1000  - $this->start * 1000;

        if ($result > $limit) {
            $message = sprintf('Slow application execution %.2f (exceeded %.2f)', $result, $result - $limit);
            $this->logger->addLog(array(
                'message'     => $message,
                'stack_trace' => array(),
                'type'        => 'SLOW',
                'code'        => 200,
            ));
        }
    }

}