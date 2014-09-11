<?php
/**
 * File Executor.php
 */

namespace Tebru\Executioner;

use Exception;
use Tebru\Executioner\Exception\TypeMismatchException;
use Tebru\Executioner\Strategy\TerminationStrategy;
use Tebru\Executioner\Strategy\WaitStrategy;
use Tebru\Logger\ExceptionLogger;

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
     * @param Attemptor $attemptor
     * @param ExceptionLogger $logger
     * @param WaitStrategy $waitStrategy
     * @param TerminationStrategy $terminationStrategy
     */
    public function __construct(
        Attemptor $attemptor,
        ExceptionLogger $logger,
        WaitStrategy $waitStrategy,
        TerminationStrategy $terminationStrategy
    ) {
        $this->attemptor = $attemptor;
        $this->logger = $logger;
        $this->waitStrategy = $waitStrategy;
        $this->terminationStrategy = $terminationStrategy;
    }

    /**
     * Try to execute code
     *
     * @return mixed
     */
    public function execute()
    {
        // tell the termination strategy we're ready to start attempting execution
        $this->terminationStrategy->start();

        // start recursive execution process and return the result
        return $this->doExecute();
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
            return $this->attemptor->attemptOperation();
        } catch (Exception $exception) {
            // tell termination strategy an attempt has been made
            $this->terminationStrategy->addAttempt();

            // check for exception that should fail immediately without retry
            $executedCallback = $this->executeExceptionDelgates($this->attemptor->getFailureExceptions(), $exception);
            if (true === $executedCallback) {
                // log the error
                $this->logger->error(
                    $this->logger->getErrorMessage() . ' [not retrying]',
                    ['exception' => $exception]
                );

                return null;
            }

            $retryableExceptions = $this->attemptor->getRetryableExceptions();

            // if no retryable exceptions are set, assume we're retrying on all other exceptions
            if (0 === count($retryableExceptions)) {
                return $this->retry($exception);
            }

            // otherwise, we'll only retry on the exceptions that have been set
            $executedCallback = $this->executeExceptionDelgates($retryableExceptions, $exception);
            if (true === $executedCallback) {
                return $this->retry($exception);
            }

            //log that we were unable to handle the exception
            $this->logger->error(
                $this->logger->getErrorMessage() . ' [no predetermined handlers]',
                ['exception' => $exception]
            );

            // rethrow exception
            throw $exception;
        }
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
    private function executeExceptionDelgates(array $exceptionDelegates, Exception $exception)
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
    private function retry(Exception $exception)
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
} 
