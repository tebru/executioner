<?php
/**
 * File AttemptBoundFactory.php 
 */

namespace Tebru\Executioner\Factory\TerminationStrategy;

use Tebru\Executioner\Strategy\Termination\AttemptBoundStrategy;

/**
 * Class AttemptBoundFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AttemptBoundFactory
{
    /**
     * Make a new AttemptBoundStrategy
     *
     * @param int $limit
     *
     * @return AttemptBoundStrategy
     */
    public function make($limit = AttemptBoundStrategy::DEFAULT_LIMIT)
    {
        return new AttemptBoundStrategy($limit);
    }
} 
