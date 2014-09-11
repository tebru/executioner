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
    public function __construct($limit = 1)
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
}
