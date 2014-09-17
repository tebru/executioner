<?php
/**
 * File Executor.php
 */

namespace Tebru\Executioner;

use BadMethodCallException;
use Exception;
use Tebru\Executioner\Exception\TypeMismatchException;
use Tebru\Executioner\Strategy\TerminationStrategy;
use Tebru\Executioner\Strategy\WaitStrategy;
use Tebru\Executioner\Logger\ExceptionLogger;

/**
 * Class Executor
 *
 * Handles executing code residing in an Attemptor object.  Will retry execution
 * if an exception occurs based on the termination strategy.  Will wait between
 * execution attempts based on the wait strategy.  Will log with defaults based
 * on the ExecutionLogger.
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Executor
{
    /**
     * Object that contains all logic for execution flow
     *
     * @var Attemptor $attemptor
     */
    private $attemptor;

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
     * Constructor
     *
     * @param ExceptionLogger $logger
     * @param WaitStrategy $waitStrategy
     * @param TerminationStrategy $terminationStrategy
     * @param Attemptor $attemptor
     */
    public function __construct(
        ExceptionLogger $logger,
        WaitStrategy $waitStrategy,
        TerminationStrategy $terminationStrategy,
        Attemptor $attemptor = null
    ) {
        $this->logger = $logger;
        $this->waitStrategy = $waitStrategy;
        $this->terminationStrategy = $terminationStrategy;
        $this->attemptor = $attemptor;
    }

    /**
     * Try to execute code
     *
     * @param Attemptor $attemptor
     *
     * @throws \BadMethodCallException
     * @return mixed
     */
    public function execute(Attemptor $attemptor = null)
    {
        if (null !== $attemptor) {
            $this->attemptor = $attemptor;
        }

        if (null === $this->attemptor) {
            throw new BadMethodCallException('Attemptor should not be null');
        }

        // reset the strategies in case they've persisted between execute calls
        $this->terminationStrategy->reset();
        $this->waitStrategy->reset();

        // tell the termination strategy we're ready to start attempting execution
        $this->terminationStrategy->start();

        // start recursive execution process and return the result
        return $this->doExecute();
    }

    /**
     * Set the logger error message
     *
     * @param string $errorMessage
     *
     * @return $this
     */
    public function setErrorMessage($errorMessage)
    {
        $this->logger->setErrorMessage($errorMessage);

        return $this;
    }

    /**
     * Set the log level for the logger
     *
     * @param mixed $logLevel
     *
     * @return $this
     */
    public function setLogLevel($logLevel)
    {
        $this->logger->setLogLevel($logLevel);

        return $this;
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
        try {
            $result = $this->attemptor->attemptOperation();

            if (!in_array($result, $this->attemptor->getFailureValues(), true)) {
                return $result;
            }

            return $this->handleFailure();
        } catch (Exception $exception) {
            return $this->handleFailure($exception);
        }
    }

    /**
     * An exception or failure value was returned
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
        $this->terminationStrategy->addAttempt();

        // if an exception wasn't thrown, just retry
        if (null === $exception) {
            return $this->retry();
        }

        // check for exception that should fail immediately without retry
        $executedCallback = $this->executeExceptionDelegates($this->attemptor->getFailureExceptions(), $exception);
        if (true === $executedCallback) {
            // log the error
            $this->logger->error(
                $this->logger->getErrorMessage() . ' [not retrying]',
                ['exception' => $exception]
            );

            return null;
        }

        $retryableExceptions = $this->attemptor->getRetryableExceptions();

        // if null is returned from retryable exceptions and an exception was thrown, quit early
        if (null === $retryableExceptions) {
            $this->unhandleable($exception);
        }

        // if no retryable exceptions are set, assume we're retrying on all other exceptions
        if (0 === count($retryableExceptions)) {
            return $this->retry($exception);
        }

        // otherwise, we'll only retry on the exceptions that have been set
        $executedCallback = $this->executeExceptionDelegates($retryableExceptions, $exception);
        if (true === $executedCallback) {
            return $this->retry($exception);
        }

        $this->unhandleable($exception);

        return null;
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
                    'Expected "%s", got "%s"',
                    'Tebru\Executioner\ExceptionDelegator',
                    get_class($exceptionDelegate)
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
        if ($this->terminationStrategy->hasFinished()) {
            // we're done attempting, log as an error and exit
            $this->logger->log(
                $this->logger->getLogLevel(),
                $this->logger->getErrorMessage(),
                [
                    'exception' => $exception,
                    'startTime' => $this->terminationStrategy->getStartedTime(),
                    'endTime' => time(),
                    'numberOfAttempts' => $this->terminationStrategy->getAttempts(),
                ]
            );

            // call 'cleanup' callback and return
            return $this->attemptor->exitOperation();
        }

        // log that we failed and are retrying
        $this->logger->info(
            $this->logger->getErrorMessage() . ' [retrying]',
            ['exception' => $exception, 'attempts' => $this->terminationStrategy->getAttempts()]
        );

        // wait based on strategy
        $this->waitStrategy->wait();

        // retry
        return $this->doExecute();
    }

    /**
     * We can't handle this exception, log it and rethrow
     *
     * @param Exception $exception
     *
     * @throws \Exception
     */
    private function unhandleable(Exception $exception)
    {
        //log that we were unable to handle the exception
        $this->logger->error(
            $this->logger->getErrorMessage() . ' [no predetermined handlers]',
            ['exception' => $exception]
        );

        // rethrow exception
        throw $exception;
    }
}
