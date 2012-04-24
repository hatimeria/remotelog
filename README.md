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
      $remotelog = Mage::registry('remotelog');
      $remotelog->addExceptionLog($reportData);
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
    $remotelogDir = 'lib/remotelog/src/Remotelog/';

    require_once $remotelogDir . 'Logger.php';
    require_once $remotelogDir . 'MagentoLogger.php';

    $remotelog = new \Remotelog\MagentoLogger('http://remotelogserver.localhost', 'Remotelog test enviroment', '/api/monitoring');
    Mage::register('remotelog', $remotelog);
    Mage::register('remotelog_enabled', false);
    register_shutdown_function(array($remotelog, 'handleMageShutdown'));

    // ... before Magento run method

    $mageRunCode = isset($_SERVER['MAGE_RUN_CODE']) ? $_SERVER['MAGE_RUN_CODE'] : '';
    $mageRunType = isset($_SERVER['MAGE_RUN_TYPE']) ? $_SERVER['MAGE_RUN_TYPE'] : 'store';

    Mage::run($mageRunCode, $mageRunType);
```

