<?php
/**
 * File ReturnRetryable.php
 */

namespace Tebru\Executioner\Attemptor;

/**
 * Class ReturnRetryable
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface ReturnRetryable
{
    /**
     * Return an array of values from that should trigger a retry
     *
     * Examples:
     *     return [null]
     *     return [false, null, -1, 'oops'];
     *
     * @return array
     */
    public function getRetryableReturns();
}
