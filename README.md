[![Build Status](https://travis-ci.org/tebru/executioner.svg?branch=master)](https://travis-ci.org/tebru/executioner)

# Executioner
This library aims to create an easy way to execute code that may throw an exception and should be reattempted.

## Installation
Run `composer require tebru/executioner:1.1.*`

## Usage
Here is a code sample, further descriptions are below

```
$attemptor = new \Foo\Bar\BazAttemptor();
$waitStrategy = new \Tebru\Executioner\Strategy\Wait\FibonacciWaitStrategy();
$terminationStrategy = new \Tebru\Executioner\Strategy\Termination\AttemptBound(5);
$logger = new \Tebru\Executioner\Logger\ExceptionLogger(new Psr\Log\NullLogger(), Psr\LogLevel::ERROR, 'My custom error message');

$executorFactory = new \Tebru\Executioner\Factory\ExecutorFactory();
$executor = $executorFactor->make($waitStrategy, $terminationStrategy);
$executor->execute($attemptor, $logger);
```

### Exception Delegator
There is a class `Tebru\Executioner\Delegate\ExceptionDelegate` that shows an example usage of the `Tebru\Executioner\ExceptionDelegator` interface.

This class aims to provide a way to execute a closure if the exception passed into the delegate method matches the type of the exception.

#### Null Closure
Use `Tebru\Executioner\Closure\NullClosure` if you want to handle an exception without specifying additional code that needs to be run.

### Creating an Attemptor
An attemptor is the class that contains all of the code that needs to be executed.  Implement the `Tebru\Executioner\Attemptor` interface.

#### Attemptor::attemptOperation()
Put any code that would normally go in a `try` block in this method.

```
$this->myService->apiCall();
```

#### Attemptor::getFailureExceptions()
This should return an array of `ExceptionDelegator` objects.  If there are exceptions that should not be retried, specify them here.

```
return [
    new \Tebru\Executioner\Delegate\ExceptionDelegate(
        '\Foo\Bar\BazException',
        function (BazException $exception) {
            $this->cleanupMethod();
        }
    ),
]
```

#### Attemptor::getRetryableExceptions()
This should return an array of `ExceptionDelegator` objects. If this method returns an empty array, we assume that all exceptions thrown should be retried.

```
return [
    new \Tebru\Executioner\Delegate\ExceptionDelegate(
        '\My\Catchable\Exception',
        function (Exception $exception) {
            $this->doSomething();
        }
    ),
    new \Tebru\Executioner\Delegate\ExceptionDelegate(
        '\Another\Catchable\Exception',
        new \Tebru\Executioner\Closure\NullClosure()
    ),
]
```

#### Attemptor::exitOperation()
Put any additional cleanup that needs to happen in this method.

```
$this->cleanupMethod();
```

### Defining a Wait Strategy
Use one of the included wait strategies or create your own using the `Tebru\Executioner\Strategy\Wait` abstract class or the `Tebru\Executioner\Strategy\WaitStrategy` interface.

Wait strategies determine how long we should wait before retrying an operation.

Included wait strategies:

- Static: Wait for the same amount of time each time.  Can define the number of seconds to wait.
- Linear: Increase time linearly.  For example wait 1 second, then 2 seconds, then 3 seconds.
- Exponential: Increase time exponentially.  Can define initial time in seconds, exponent to use, and number of seconds to increment each time.
- Logarithmic: Increase time based on a logarithmic curve.  Can define initial starting time in seconds, number of seconds to increment by, and the base to use.
- Fibonacci: Increase time based on the fibonacci sequence.  Can define initial starting pair.  Initial wait time will be the sum.

### Defining a Termination Strategy
A termination strategy will determine when we should stop retrying the operation.

Included termination strategies:

- Attempt-bound: Set the number of attempts we should make before quitting
- Time-bound: Set the time in seconds we should wait before quitting

### Exception Logging
The `Tebru\Executioner\Logger\ExceptionLogger` implements the PSR LoggerInterface and adds fields for the error message and log level that should be used.  It also takes a LoggerInterface that is used to do the actual logging.
