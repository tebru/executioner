<?php
/**
 * File ExceptionRetryable.php 
 */

namespace Tebru\Executioner\Attemptor;

use Tebru\Executioner\ExceptionDelegator;

/**
 * Interface ExceptionRetryable
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface ExceptionRetryable
{
    /**
     * Set up exceptions that should be retried
     *
     * If an empty array is returned, we will assume that all exceptions that aren't handled
     * as failure exceptions will be caught and retried.
     *
     * The expected return format is an array of Tebru\Executioner\ExceptionDelegator objects.
     *
     * @return ExceptionDelegator[]
     */
    public function getRetryableExceptions();
}
