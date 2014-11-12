<?php
/**
 * File AttemptBoundTerminationStrategyFactory.php
 */

namespace Tebru\Executioner\Factory\Termination;

use Tebru\Executioner\Strategy\Termination\AttemptBoundTerminationStrategy;

/**
 * Class AttemptBoundTerminationStrategyFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AttemptBoundTerminationStrategyFactory
{
    /**
     * Make a new AttemptBoundTerminationStrategy
     *
     * @param int $limit
     *
     * @return AttemptBoundTerminationStrategy
     */
    public function make($limit = AttemptBoundTerminationStrategy::DEFAULT_LIMIT)
    {
        return new AttemptBoundTerminationStrategy($limit);
    }
} 
