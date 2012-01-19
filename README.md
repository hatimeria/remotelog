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