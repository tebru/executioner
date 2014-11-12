<?php
/**
 * File SingleAttemptTerminationStrategy.php
 */

namespace Tebru\Executioner\Strategy\Termination;

use Tebru\Executioner\Strategy\AttemptAwareTerminationStrategy;
use Tebru\Executioner\Strategy\Termination;

/**
 * Class SingleAttemptTerminationStrategy
 *
 * Use this strategy if you do not want to handle multiple attempts
 *
 * @author Nate Brunette <n@tebru.net>
 */
class SingleAttemptTerminationStrategy implements AttemptAwareTerminationStrategy
{
    private $attempts;

    /**
     * Inform the termination strategy we're starting the execution process
     *
     * @return null
     */
    public function reset()
    {
        $this->attempts = 0;
    }

    /**
     * Always return true, we're only trying once
     *
     * @return bool
     */
    public function hasFinished()
    {
        return true;
    }

    /**
     * Add an attempt to the attempt counter
     *
     * @return null
     */
    public function addAttempt()
    {
        ++$this->attempts;
    }

    /**
     * Get the current number of attempts
     *
     * @return int
     */
    public function getAttempts()
    {
        return $this->attempts;
    }

}
