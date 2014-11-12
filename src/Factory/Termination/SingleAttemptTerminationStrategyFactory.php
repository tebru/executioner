<?php
/**
 * File SingleAttemptTerminationStrategyFactory.php
 */

namespace Tebru\Executioner\Factory\Termination;

use Tebru\Executioner\Strategy\Termination\SingleAttemptTerminationStrategy;

/**
 * Class SingleAttemptTerminationStrategyFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class SingleAttemptTerminationStrategyFactory
{
    /**
     * Make a new SingleAttemptTerminationStrategy
     *
     * @return SingleAttemptTerminationStrategy
     */
    public function make()
    {
        return new SingleAttemptTerminationStrategy();
    }
} 
