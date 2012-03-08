Remotelog
=========

Monolog compatible logging handler posting array of parameters to configured host as application/json data

## Usage

### Using Logger class itself

``` php
<?php

    use Remotelog\Logger;

    $logger = new Logger('remotelogserver.localhost', 'Remotelog test enviroment', '/api/monitoring');

    // log exception object
    $logger->addLog(new \Exception('Very critical information!'));

    // log an array of parameters
    $logger->addLog(array(
        'message' => 'Very critical information!',
        'type'    => 'CRITICAL',
    ));

    // place parameter (Remotelog test enviroment) is added automatically to sent parameters

```

### Using as Monolog handler

``` php
<?php

    use Monolog\Logger;
    use Remotelog\Handler\RemotelogHandler;

    // create a log channel
    $log = new Logger('name');
    $log->pushHandler(new RemotelogHandler('remotelogserver.localhost', 'Remotelog test enviroment', '/api/monitoring', Logger::ERROR));

    // add records to the log
    $log->addWarning('Foo');
    $log->addError('Bar');

```

### Using in Magento

If you would like to implement remotelog lib into Magento you must add couple of lines into two Magento files.
First of all download remotelog and unpack it into Magento lib directory.
And update files;

``` php
<?php
   // errors/report.php

   require_once 'processor.php';

   $processor = new Error_Processor();

   if (isset($reportData) && is_array($reportData)) {
      // ... begining
      $remotelogDir = Mage::getBaseDir('lib') . '/remotelog/src/Remotelog/';

      require_once $remotelogDir . 'Logger.php';
      require_once $remotelogDir . 'MagentoLogger.php';

      $remotelog = new \Remotelog\MagentoLogger('http://extranet.hatimeria.com', 'shop.whiteandblack.pl test', '/api/monitoring');
      $remotelog->addLog($reportData);
      // ... end
      $processor->saveReport($reportData);
   }

$processor->processReport();

```

These part of code will take care of your fatal errors. If you don't like to catch fatal errors you don't have to add it. 

``` php
<?php

    // index.php

    // ...
    function handleMageShutdown()
    {
        $error = error_get_last();
        if (is_null($error) || ($error instanceof \ErrorException)) return;

        $message = sprintf('Fatal error: %s in %s on line %s', $error['message'], $error['file'], $error['line']);

        $e = new ErrorException($message);
        Mage::printException($e);
    }

    register_shutdown_function('handleMageShutdown');

    // before Magento run method

    $mageRunCode = isset($_SERVER['MAGE_RUN_CODE']) ? $_SERVER['MAGE_RUN_CODE'] : '';
    $mageRunType = isset($_SERVER['MAGE_RUN_TYPE']) ? $_SERVER['MAGE_RUN_TYPE'] : 'store';

    Mage::run($mageRunCode, $mageRunType);
```

