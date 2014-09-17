<?php
/**
 * File AttemptBoundStrategy.php
 */

namespace Tebru\Executioner\Strategy\Termination;

use Tebru\Executioner\Strategy\Termination;

/**
 * Class AttemptBoundStrategy
 *
 * Use this strategy to determine when to exit based on how many times we've tried
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class AttemptBoundStrategy extends Termination
{
    /**#@+
     * Default values
     */
    const DEFAULT_LIMIT = 1;
    /**#@-/

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
     * If the number of attempts exceeds the limit, return true
     *
     * @return bool
     */
    public function hasFinished()
    {
        return $this->getAttempts() >= $this->limit;
    }

    /**
     * Reset this strategy
     *
     * @return null
     */
    public function reset()
    {
        $this->setAttempts(0);
    }
}
