<?php
/**
 * File TerminationStrategy.php
 */

namespace Tebru\Executioner\Strategy;

/**
 * Interface TerminationStrategy
 *
 * Classes that implement this interface are able to define how the Executor finishes execution
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
     * Reset this strategy
     *
     * @return null
     */
    public function reset();
}
