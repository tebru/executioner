<?php
/**
 * File AttemptAwareTerminationStrategy.php 
 */

namespace Tebru\Executioner\Strategy;

/**
 * Interface AttemptAwareTerminationStrategy
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface AttemptAwareTerminationStrategy extends TerminationStrategy
{
    /**
     * Add an attempt to the attempt counter
     *
     * @return null
     */
    public function addAttempt();

    /**
     * Get the current number of attempts
     *
     * @return int
     */
    public function getAttempts();
}
