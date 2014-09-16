<?php
/**
 * File Attemptor.php
 */

namespace Tebru\Executioner;

/**
 * Interface Attemptor
 *
 * Classes that implement this interface will be usable with Tebru\Executioner\Executor
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface Attemptor
{
    /**
     * Return an array of values from attemptOperation() that should
     * trigger a retry
     *
     * Examples:
     *     return [null]
     *     return [false, null, -1, 'oops'];
     *
     * @return array
     */
    public function getFailureValues();

    /**
     * Put code here that would normally go in a try block
     *
     * @return mixed
     */
    public function attemptOperation();

    /**
     * Set up exceptions that should be retried
     *
     * If an empty array is returned, we will assume that all exceptions that aren't handled
     * as failure exceptions will be caught and retried.
     *
     * The expected return format is an array of Tebru\Executioner\ExceptionDelegator objects.
     * Use a Tebru\Executioner\Closure\NullClosure when there isn't additional handler code
     * needed.
     *
     * @return ExceptionDelegator[]
     */
    public function getRetryableExceptions();

    /**
     * Set up exceptions that should fail immediately without retry
     *
     * The expected return format is an array of Tebru\Executioner\ExceptionDelegator objects.
     * Use a Tebru\Executioner\Closure\NullClosure when there isn't additional handler code
     * needed.
     *
     * @return ExceptionDelegator[]
     */
    public function getFailureExceptions();

    /**
     * Put code here that should be run if we retry and fail
     *
     * @return mixed
     */
    public function exitOperation();
} 
