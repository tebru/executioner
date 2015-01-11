<?php
/**
 * File Executor.php
 */

namespace Tebru\Executioner;

use BadMethodCallException;
use Exception;
use Psr\Log\LogLevel;
use Tebru\Executioner\Attemptor\ExceptionRetryable;
use Tebru\Executioner\Attemptor\ImmediatelyFailable;
use Tebru\Executioner\Attemptor\ReturnRetryable;
use Tebru\Executioner\Delegate\ExceptionDelegate;
use Tebru\Executioner\Exception\FailedException;
use Tebru\Executioner\Exception\TypeMismatchException;
use Tebru\Executioner\Strategy\AttemptAwareTerminationStrategy;
use Tebru\Executioner\Strategy\Termination\AttemptBoundTerminationStrategy;
use Tebru\Executioner\Strategy\TerminationStrategy;
use Tebru\Executioner\Strategy\TimeAwareTerminationStrategy;
use Tebru\Executioner\Strategy\Wait\StaticWaitStrategy;
use Tebru\Executioner\Strategy\WaitStrategy;
use Tebru\Executioner\Logger\ExceptionLogger;

/**
 * Class Executor
 *
 * Wrapper that handles the executing and retrying of an operation
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Executor
{
    /**
     * Callable to execute arbitrary code
     *
     * @var callable $attemptor
     */
    private $attemptor;

    /**
     * Arguments that should be passed into the attemptor
     *
     * @var array $attemptorArguments
     */
    private $attemptorArguments = [];

    /**
     * A LoggerInterface containing defaults for exception logging
     *
     * @var ExceptionLogger $logger
     */
    private $logger;

    /**
     * Determines how long we should wait between attempts
     *
     * @var WaitStrategy $waitStrategy
     */
    private $waitStrategy;

    /**
     * Determines when we're done attempting
     *
     * @var TerminationStrategy terminationStrategy
     */
    private $terminationStrategy;

    /**
     * An array of exceptions we should retry on
     *
     * An empty array signifies we should retry on every exception
     *
     * @var array $retryableExceptions
     * @see \Tebru\Executioner\Attemptor\ExceptionRetryable
     */
    private $retryableExceptions = [];

    /**
     * An array of return values we should retry on
     *
     * @var array $retryableReturns
     * @see \Tebru\Executioner\Attemptor\ReturnRetryable
     */
    private $retryableReturns = [];

    /**
     * An array of exceptions that should fail without retrying
     *
     * @var array $immediatelyFailableExceptions
     * @see \Tebru\Executioner\Attemptor\ImmediatelyFailable
     */
    private $immediatelyFailableExceptions = [];

    /**
     * A callable that is executed before returning from execute
     *
     * @var callable $cleanupCallback
     */
    private $cleanupCallback;

    /**
     * Constructor
     *
     * @param ExceptionLogger $logger
     * @param WaitStrategy $waitStrategy
     * @param TerminationStrategy $terminationStrategy
     * @param callable $attemptor
     */
    public function __construct(
        ExceptionLogger $logger = null,
        WaitStrategy $waitStrategy = null,
        TerminationStrategy $terminationStrategy = null,
        callable $attemptor = null
    ) {
        $this->logger = $logger;
        $this->waitStrategy = $waitStrategy;
        $this->terminationStrategy = $terminationStrategy;
        $this->attemptor = $attemptor;
    }

    /**
     * Will sleep for $seconds between attempts
     *
     * Shortcut to set a static wait strategy
     *
     * @param $seconds
     * @return $this
     */
    public function sleep($seconds)
    {
        $this->setWaitStrategy(new StaticWaitStrategy($seconds));

        return $this;
    }

    /**
     * Will stop after $attempts number of attempts
     *
     * Shortcut to set an attempt bound termination strategy
     *
     * @param $attempts
     * @return $this
     */
    public function limit($attempts)
    {
        $this->setTerminationStrategy(new AttemptBoundTerminationStrategy($attempts));

        return $this;
    }

    /**
     * Set a wait strategy
     *
     * @param WaitStrategy $waitStrategy
     * @return $this
     */
    public function setWaitStrategy(WaitStrategy $waitStrategy)
    {
        $this->waitStrategy = $waitStrategy;

        return $this;
    }

    /**
     * Set a termination strategy
     *
     * @param TerminationStrategy $terminationStrategy
     * @return $this
     */
    public function setTerminationStrategy(TerminationStrategy $terminationStrategy)
    {
        $this->terminationStrategy = $terminationStrategy;

        return $this;
    }

    /**
     * Set a logger
     *
     * @param ExceptionLogger $logger
     * @return $this
     */
    public function setLogger(ExceptionLogger $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Set retryable exceptions
     *
     * If string[] is passed in, will create @see \Tebru\Executioner\Delegate\ExceptionDelegate objects with
     * a @see \Tebru\Executioner\Closure\NullClosure as the callback.
     *
     * @param ExceptionDelegate[]|string[] $retryableExceptions
     * @return $this
     * @throws TypeMismatchException
     */
    public function setRetryableExceptions(array $retryableExceptions)
    {
        $this->retryableExceptions = $this->setExceptionDelegates($retryableExceptions);

        return $this;
    }

    /**
     * Set retryable returns
     *
     * @param array $retryableReturns
     * @return $this
     */
    public function setRetryableReturns(array $retryableReturns)
    {
        $this->retryableReturns = $retryableReturns;

        return $this;
    }

    /**
     * Set immediately failable exceptions
     *
     * If string[] is passed in, will create @see \Tebru\Executioner\Delegate\ExceptionDelegate objects with
     * a @see \Tebru\Executioner\Closure\NullClosure as the callback.
     *
     * @param array $immediatelyFailableExceptions
     * @return $this
     * @throws TypeMismatchException
     */
    public function setImmediatelyFailableExceptions(array $immediatelyFailableExceptions)
    {
        $this->immediatelyFailableExceptions = $this->setExceptionDelegates($immediatelyFailableExceptions);

        return $this;
    }

    /**
     * Set cleanup callback
     *
     * @param callable $cleanupCallback
     * @return $this
     */
    public function setCleanupCallback(callable $cleanupCallback)
    {
        $this->cleanupCallback = $cleanupCallback;

        return $this;
    }

    /**
     * Get retryable exceptions
     *
     * @return ExceptionDelegator[]
     */
    private function getRetryableExceptions()
    {
        if ($this->attemptor instanceof ExceptionRetryable) {
            return $this->attemptor->getRetryableExceptions();
        }

        return $this->retryableExceptions;
    }

    /**
     * Get retryable returns
     *
     * @return array
     */
    private function getRetryableReturns()
    {
        if ($this->attemptor instanceof ReturnRetryable) {
            return $this->attemptor->getRetryableReturns();
        }

        return $this->retryableReturns;
    }

    /**
     * Get immediately failable exceptions
     *
     * @return ExceptionDelegator[]
     */
    private function getImmediatelyFailableExceptions()
    {
        if ($this->attemptor instanceof ImmediatelyFailable) {
            return $this->attemptor->getImmediatelyFailableExceptions();
        }

        return $this->immediatelyFailableExceptions;
    }

    /**
     * Helper method that creates an ExceptionDelegate[]
     *
     * If a string value is passed in, it will create an ExceptionDelegate object.
     *
     * @param array $exceptionDelegates
     * @return ExceptionDelegate[]
     * @throws TypeMismatchException
     */
    private function setExceptionDelegates(array $exceptionDelegates)
    {
        foreach ($exceptionDelegates as $key => $exceptionDelegate) {
            // if it's a string, create a new ExceptionDelegate
            if (is_string($exceptionDelegate)) {
                $exceptionDelegates[$key] = new ExceptionDelegate($exceptionDelegate);

                continue;
            }

            // if it is not the right type, throw an exception
            if (!$exceptionDelegate instanceof ExceptionDelegator) {
                throw new TypeMismatchException(sprintf(
                    'Expected "Tebru\Executioner\ExceptionDelegator" or string, got "%s"', get_class($exceptionDelegate)
                ));
            }
        }

        return $exceptionDelegates;
    }

    /**
     * Try to execute code
     *
     * @param callable $attemptor The code getting attempted
     * @param array $attemptorArguments
     * @return mixed
     */
    public function execute($attemptor = null, array $attemptorArguments = [])
    {
        if (null !== $attemptor) {
            $this->attemptor = $attemptor;
        }

        if (null === $this->attemptor) {
            throw new BadMethodCallException('Attemptor must not be null');
        }

        $this->attemptorArguments = $attemptorArguments;

        // handles logic for starting the process before any retrying occurs
        $this->reset();

        // start recursive execution process and return the result
        $result = $this->doExecute();

        // call cleanup callback
        $cleanupCallback = $this->cleanupCallback;
        if (is_callable($cleanupCallback)) {
            $cleanupCallback();
        }

        return $result;
    }

    /**
     * Recursively attempt execution
     *
     * @return mixed
     *
     * @throws Exception If we could not determine how to handle a thrown exception
     */
    private function doExecute()
    {
        // attempt execution
        try {
            $result = call_user_func_array($this->attemptor, $this->attemptorArguments);
        } catch (Exception $exception) {
            return $this->handleFailure($exception);
        }

        // if the attempt return is not in list of returns we should retry on, just return the result
        $isRetryableFailure = in_array($result, $this->getRetryableReturns(), true);

        // if result is in the array of failure values
        if (true === $isRetryableFailure) {
            return $this->handleFailure();
        }

        return $result;
    }

    /**
     * An exception was thrown or failure value was returned
     *
     * @param Exception $exception
     *
     * @return mixed
     *
     * @throws Exception If we cannot handle the exception
     * @throws TypeMismatchException If array values of not ExceptionDelegates
     */
    private function handleFailure(Exception $exception = null)
    {
        // tell termination strategy an attempt has been made
        $this->addAttempt();

        // if an exception wasn't thrown, just retry
        if (null === $exception) {
            return $this->retry();
        }

        // check for exception that should fail immediately without retry
        $executedCallback = $this->executeExceptionDelegates($this->getImmediatelyFailableExceptions(), $exception);

        // if callbacks were executed, we're immediately failing
        if (true === $executedCallback) {
            $this->log('error', '[failure exception found]', ['exception' => $exception]);

            // rethrow
            throw $exception;
        }

        // if no retryable exceptions are set, assume we're retrying on all other exceptions
        $retryableExceptions = $this->getRetryableExceptions();
        if (0 === count($retryableExceptions)) {
            return $this->retry($exception);
        }

        // otherwise, we'll only retry on the exceptions that have been set
        $executedCallback = $this->executeExceptionDelegates($retryableExceptions, $exception);

        // if the exception matches an exception we're retrying on
        if (true === $executedCallback) {
            return $this->retry($exception);
        }

        //log that we were unable to handle the exception
        $this->log(LogLevel::ERROR, '[no predetermined handlers]', ['exception' => $exception]);

        // rethrow exception
        throw $exception;
    }

    /**
     * Loop through array of DelegateExceptions and call delegate
     *
     * Returns true if any callbacks were executed and false otherwise
     *
     * @param ExceptionDelegator[] $exceptionDelegates
     * @param Exception $exception
     *
     * @throws TypeMismatchException
     * @return bool
     */
    private function executeExceptionDelegates(array $exceptionDelegates, Exception $exception = null)
    {
        $exceptionCallbacksCalled = 0;

        // check if we're not retrying this kind of exception
        foreach ($exceptionDelegates as $exceptionDelegate) {
            // make sure we have the right objects
            if (!$exceptionDelegate instanceof ExceptionDelegator) {
                throw new TypeMismatchException(sprintf(
                    'Expected "Tebru\Executioner\ExceptionDelegator", got "%s"', get_class($exceptionDelegate)
                ));
            }

            $calledCallback = $exceptionDelegate->delegate($exception);
            if (true === $calledCallback) {
                ++$exceptionCallbacksCalled;
            }
        }

        return ($exceptionCallbacksCalled > 0) ? true : false;
    }

    /**
     * Retry logic
     *
     * If we haven't finished attempting execution, wait, retry and log attempt.  If
     * we have finished attempting, log based on ExceptionLogger values and run cleanup
     * code.
     *
     * @param Exception $exception
     *
     * @return mixed
     */
    private function retry(Exception $exception = null)
    {
        if ($this->hasFinished()) {
            return $this->retryFinishedAndFailed($exception);
        }

        // log that we failed and are retrying
        $context = ['exception' => $exception];
        if ($this->terminationStrategy instanceof AttemptAwareTerminationStrategy) {
            $context['attempts'] = $this->terminationStrategy->getAttempts();
        }

        $this->log(LogLevel::INFO, '[retrying]', $context);

        // wait based on strategy
        $this->wait();

        // retry
        return $this->doExecute();
    }

    /**
     * Handles logic for logging failure and returns false
     *
     * @param Exception $exception
     * @throws FailedException
     */
    private function retryFinishedAndFailed(Exception $exception = null)
    {
        // we're done attempting, log as an error and exit
        $context = ['exception' => $exception];

        if ($this->terminationStrategy instanceof TimeAwareTerminationStrategy) {
            $context['startTime'] = $this->terminationStrategy->getStartedTime();
            $context['endTime'] = $this->terminationStrategy->getCurrentTime();
        }

        if ($this->terminationStrategy instanceof AttemptAwareTerminationStrategy) {
            $context['numberOfAttempts'] = $this->terminationStrategy->getAttempts();
        }

        $this->log(null, null, $context);

        throw new FailedException('Retrying unsuccessful', 0, $exception);
    }

    /*------------------------------------
     * PASS-THROUGH TO OTHER OTHER OBJECTS
     *-----------------------------------*/

    /**
     * Log an error
     *
     * @param null $level
     * @param string $messageSuffix
     * @param array $context
     */
    private function log($level = null, $messageSuffix = '', $context = [])
    {
        if (null === $this->logger) {
            return null;
        }

        if (null === $level) {
            $level = $this->logger->getLogLevel();
        }

        $message = (null === $messageSuffix)
            ? $this->logger->getErrorMessage()
            : $this->logger->getErrorMessage() . ' ' . $messageSuffix;

        $this->logger->log($level, $message, $context);
    }

    /**
     * Add attempt if termination strategy is attempt aware
     */
    private function addAttempt()
    {
        if ($this->terminationStrategy instanceof AttemptAwareTerminationStrategy) {
            $this->terminationStrategy->addAttempt();
        }
    }

    /**
     * Reset if termination and wait strategies exists
     */
    private function reset()
    {
        if (null !== $this->terminationStrategy) {
            // tell the termination strategy we're ready to start attempting execution
            $this->terminationStrategy->reset();
        }

        if (null !== $this->waitStrategy) {
            // tell the termination strategy we're ready to start attempting execution
            $this->waitStrategy->reset();
        }
    }

    /**
     * Returns false if we have not finished or termination strategy not set
     *
     * @return bool
     */
    private function hasFinished()
    {
        return (null !==$this->terminationStrategy) && $this->terminationStrategy->hasFinished();
    }

    /**
     * Wait if strategy exists
     */
    private function wait()
    {
        if (null !== $this->waitStrategy) {
            $this->waitStrategy->wait();
        }
    }
}
