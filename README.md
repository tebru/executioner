[![Build Status](https://travis-ci.org/tebru/executioner.svg?branch=master)](https://travis-ci.org/tebru/executioner)

# Executioner
This library aims to create an easy way to execute code that may throw an exception and should be reattempted.

## Installation
Run `composer require tebru/executioner:~0.1`

## Basic Usage
The library can be used as simply as this

```
<?php

use Tebru\Executioner;

$executor = new Executor();
$result = $executor->execute(function () { /* code that may throw an exception */ });
```

Which will retry on all exceptions without stopping.

You can also pass in arguments that will be called on the callable

```
<?php

use Tebru\Executioner;

$executor = new Executor();
$result = $executor->execute(
    function ($foo) { $foo->doSomething(); },
    [$foo]
);
```
While this is the same as `use` in this case, if your callable is an object, this becomes useful.


*Please note: any of the following dependencies can be injected through the constructor or a setter*

### Executor Logging
If you would like to log what's happening, you need to use a `\Tebru\Executioner\Logger\ExceptionLogger`

This class accepts a PSR LoggerInterface, log level, and default log message.  It aims to configure a logger since there isn't control over the logger during execution.

All arguments are optional.  If a logger is not passed in, a `\Tebru\Executioner\Logger\PhpErrorLogLogger` will be used.  This logger uses the php function `error_log()` to log error messages.

```
<?php

use Tebru\Executioner;
use Tebru\Executioner\Logger;

$logger = new ExceptionLogger();
$executor = new Executor($logger);
$result = $executor->execute(function () { /* code that may throw an exception */ });
```

### Wait Strategy
Use one of the included wait strategies or create your own using the `Tebru\Executioner\Strategy\WaitStrategy` interface.

Wait strategies determine how long the application will wait before retrying.

Included wait strategies:

- Static: Wait for the same amount of time each time.  Can define the number of seconds to wait.
- Linear: Increase time linearly.  For example wait 1 second, then 2 seconds, then 3 seconds.
- Exponential: Increase time exponentially.  Can define initial time in seconds, exponent to use, and number of seconds to increment each time.
- Logarithmic: Increase time based on a logarithmic curve.  Can define initial starting time in seconds, number of seconds to increment by, and the base to use.
- Fibonacci: Increase time based on the fibonacci sequence.  Can define initial starting pair.  Initial wait time will be the sum.

```
<?php

use Tebru\Executioner;
use Tebru\Executioner\Logger;
use Tebru\Executioner\Strategy\Wait;

$logger = new ExceptionLogger();
$waitStrategy = new FibonacciWaitStrategy();
$executor = new Executor(null, $waitStrategy);
$executor->setLogger($logger);
$result = $executor->execute(function () { /* code that may throw an exception */ });
```

### Termination Strategy
A termination strategy will determine when to stop retrying.

Included termination strategies:

- Attempt-bound: Set the number of attempts we should make before quitting
- Time-bound: Set the time in seconds we should wait before quitting

```
<?php

use Tebru\Executioner;
use Tebru\Executioner\Logger;
use Tebru\Executioner\Strategy\Wait;
use Tebru\Executioner\Strategy\Termination;

$logger = new ExceptionLogger();
$waitStrategy = new FibonacciWaitStrategy();
$terminationStrategy = new TimeBoundStrategy(3600);
$executor = new Executor(null, null, $terminationStrategy);
$executor->setLogger($logger);
$executor->setWaitStrategy($waitStrategy);
$result = $executor->execute(function () { /* code that may throw an exception */ });
```

## Attemptor
The attemptor is the thing responsible for executing code you might find in a `try` block.

The easiest way to is to pass in a closure to the `execute()` method, which has been demonstrated before.  However it can also be an object that uses the `__invoke()` magic method.  An interface `\Tebru\Executioner\Attemptor\Invokeable` has been created for convenience.

```
<?php

use Tebru\Executioner;
use Tebru\Executioner\Logger;
use Tebru\Executioner\Strategy\Wait;
use Tebru\Executioner\Strategy\Termination;

$logger = new ExceptionLogger();
$waitStrategy = new FibonacciWaitStrategy();
$terminationStrategy = new TimeBoundStrategy(3600);
$attemptor = new MyInvokeableClass(); // must be created
$executor = new Executor(null, null, null, $attemptor);
$executor->setLogger($logger);
$executor->setWaitStrategy($waitStrategy);
$executor->setTerminationStrategy($terminationStrategy);
$result = $executor->execute();
```

As demonstrated, if an attemptor is already set, it does not need to be passed into the `execute()` method.

### ExceptionRetryable
The `\Tebru\Executioner\Attemptor\ExceptionRetryable` interface has one method `getRetryableExceptions()`.

This specifies exceptions that, if thrown, should be run through the retry logic.  Optionally, a callback can be set that allows for some additional handling if that exception is thrown.

This method should return an array of `\Tebru\Executioner\Delegate\ExceptionDelegate` objects.

### ImmediatelyFailable
The `\Tebru\Executioner\Attemptor\ImmediatelyFailable` interface has one method `getImmediatelyFailableExceptions()`.

This specifies exceptions that, if thrown, should cause the retrying to stop right away.  Optionally, a callback can be set that allows for some additional handling if that exception is thrown.

This method should return an array of `\Tebru\Executioner\ExceptionDelegator` objects.

### ExceptionDelegate
A `\Tebru\Executioner\Delegate\ExceptionDelegate` implements `\Tebru\Executioner\ExceptionDelegator` and takes a class name and a callable as constructor parameters.

When `delegate()` is called, the method will check the given exception against the class name.  If it matches, it will run the callback if it exists.  It will return true on a match and false otherwise.

The executor has two methods: `setRetryableExceptions()` and `setImmediatelyFailableExceptions()`.  Both require an array.  If any of the array values are strings, they will be converted to `ExceptionDelegate` objects with a `\Tebru\Executioner\Closure\NullClosure` used as the callback.

```
<?php

use Tebru\Executioner;
use Tebru\Executioner\Delegate;

$executor = new Executor();
$executor->setImmediatelyFailableExceptions([\InvalidArgumentException::class]);
$executor->setRetryableExceptions([new ExceptionDelegate(\Exception::class), \BadMethodCallException::class]);
$result = $executor->execute();
```

### ReturnRetryable
The `\Tebru\Executioner\Attemptor\ReturnRetryable` interface has one method `getRetryableReturns()`.

This specifies an array of values that, if returned, should trigger the retry logic.

### Attemptor
Here is an example implementation of the `\Tebru\Executioner\Attemptor` interface. This interface is available for convenience.

```
<?php

namespace Foo;

use Tebru\Executioner;
use Tebru\Executioner\Delegate;

class Bar implements Attemptor
{
    public function __invoke(Baz $baz)
    {
        return $baz->doSomething();
    }
    
    public function getRetryableExceptions()
    {
        // this is the same as returning []
        return [new DelegateException(\Exception::class)];
    }
    
    public function getImmediatelyFailableExceptions()
    {
        return [
            new DelegateException(
                \UnexpectedArgumentException::class,
                function () { // do something else on UnexpectedArgumentException }
            )
        ];
    }
    
    public function getRetryableReturns()
    {
        return [false, 0, null];
    }
}
```

This Bar object could be set on or passed into the executor instead of an anonymous function. Can also choose to implement fewer interfaces if needed.

## Available Executor Methods
There are other methods available on an Executor that haven't been referenced yet.

### Sleep
This method will create a `\Tebru\Executioner\Strategy\Wait\StaticWaitStrategy` with the number of seconds passed in.

```
$executor->sleep(2);
```

### Limit
This method  will create a `\Tebru\Executioner\Strategy\Termination\AttemptBoundStrategy` with the max number attempts allowed.

```
$executor->limit(5);
```

### Cleanup
This method accepts a callable which will get run before the return of `execute()`.

```
$executor->cleanup(function () use ($logger) { $logger->info('Logging'); });
```
