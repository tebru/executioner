<?php
/**
 * File TerminationStrategy.php
 */

namespace Tebru\Executioner\Strategy;

/**
 * Interface TerminationStrategy
 *
 * Classes that implement this interface are able to define
 * how the Executor finishes execution
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface TerminationStrategy
{
    /**
     * Returns true if we've finished trying based on implemented strategy
     *
     * @return bool True if we're done attempting, false if we're not
     */
    public function hasFinished();

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

    /**
     * Inform the termination strategy we're starting the execution process
     *
     * @return null
     */
    public function start();

    /**
     * Get the started timestamp
     *
     * @return int
     */
    public function getStartedTime();
}
