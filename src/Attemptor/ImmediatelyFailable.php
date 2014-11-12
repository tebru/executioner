<?php
/**
 * File ImmediatelyFailable.php 
 */

namespace Tebru\Executioner\Attemptor;

use Tebru\Executioner\ExceptionDelegator;

/**
 * Interface ImmediatelyFailable
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface ImmediatelyFailable
{
    /**
     * Set up exceptions that should fail immediately without retry
     *
     * The expected return format is an array of Tebru\Executioner\ExceptionDelegator objects.
     *
     * @return ExceptionDelegator[]
     */
    public function getImmediatelyFailableExceptions();
}
