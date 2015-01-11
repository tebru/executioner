[![Build Status](https://travis-ci.org/tebru/executioner.svg?branch=master)](https://travis-ci.org/tebru/executioner)

# Executioner
This library aims to create an easy way to execute code that may throw an exception and should be reattempted.

## Installation
Run `composer require tebru/executioner:dev-master`

## Basic Usage
The library can be used as simply as this

```
<?php

use Tebru\Executioner;

$executor = new Executor();
$result = $executor->execute(2, function () { /* code that may throw an exception */ });
```

Which will retry twice on all exceptions.  Failure to execute without throwing an exception will cause a `\Tebru\Executioner\Exception\FailedException` to be thrown.  Be sure to wrap your `execute()` call in a try/catch if you do not want that exception to propagate.

## Events
Events are used to provides hooks/insight into the operations.  The 4 events are:

- beforeAttempt
- afterAttempt
- failedAttempt
- endAttempt

This library uses the Symfony2 event dispatcher.  Feel free to use the included subscribers or create your own.

Use the listener methods on the Executor to target one event, and use subscribers to target multiple events.

### Delay between attempts

```
<?php

use Tebru\Executioner;
use Tebru\Executioner\Subscriber\WaitSubscriber;

$waitStrategy = new \Tebru\Executioner\Strategy\WaitStrategy();

$executor = new Executor();
$executor->addSubscriber(new WaitSubscriber($waitStrategy));
$result = $executor->execute(2, function () { /* code that may throw an exception */ });
```

There are two wait strategies included:

- Tebru\Executioner\Strategy\StaticWaitStrategy -- Waits for a set amount of time between each attempt
- Tebru\Executioner\Strategy\ExponentialBackoffStrategy -- On average, exponentially waits longer between each attempt

### Logging

```
<?php

use Tebru\Executioner;
use Tebru\Executioner\Subscriber\LoggerSubscriber;

$logger = new \Psr\Log\LoggerInterface(); // the only requirement is you need a ps-3 compatible logger

$executor = new Executor();
$executor->addSubscriber(new LoggerSubscriber('name-of-this-logger-to-distinguish-it-in-the-logs', $logger));
$result = $executor->execute(2, function () { /* code that may throw an exception */ });
```

### Retry on non-exceptions

```
<?php

use Tebru\Executioner;
use Tebru\Executioner\Subscriber\ReturnSubscriber;

$executor = new Executor();
$executor->addSubscriber(new ReturnSubscriber([false]));
$result = $executor->execute(2, function () { return false; });
```
### Shortcuts
To make life easier, there are some helper methods to create some of the included subscribers

Add a LoggerSubscriber

```
Executor::addLogger($name, LoggerInterface $logger)
```

Add a WaitSubscriber using the StaticWaitStrategy

```
Executor::addWait($seconds)
```

Add a WaitSubscriber

```
Executor::addWaitStrategy(WaitStrategy $waitStrategy)
```
