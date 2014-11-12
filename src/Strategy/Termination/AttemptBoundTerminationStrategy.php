<?php
/**
 * File AttemptBoundTerminationStrategy.php
 */

namespace Tebru\Executioner\Strategy\Termination;

use Tebru\Executioner\Strategy\AttemptAwareTerminationStrategy;
use Tebru\Executioner\Strategy\Termination;

/**
 * Class AttemptBoundTerminationStrategy
 *
 * Use this strategy to determine when to exit based on number of attempts
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class AttemptBoundTerminationStrategy implements AttemptAwareTerminationStrategy
{
    /**#@+
     * Default values
     */
    const DEFAULT_LIMIT = 1;
    /**#@-*/

    /**
     * Number of attempts
     *
     * @var int $attempts
     */
    private $attempts;

    /**
     * How many attempts we should make before quitting
     *
     * @var int limit
     */
    private $limit;

    /**
     * Constructor
     *
     * @param int $limit
     */
    public function __construct($limit = self::DEFAULT_LIMIT)
    {
        $this->limit = $limit;
    }

    /**
     * Called once at the very beginning of the process
     *
     * @return null
     */
    public function reset()
    {
        $this->attempts = 0;
    }

    /**
     * If the number of attempts exceeds the limit, return true
     *
     * @return bool
     */
    public function hasFinished()
    {
        return $this->getAttempts() >= $this->limit;
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
