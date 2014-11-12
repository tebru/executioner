<?php
/**
 * File StaticWaitStrategyFactory.php
 */

namespace Tebru\Executioner\Factory\Wait;

use Tebru\Executioner\Strategy\Wait\StaticWaitStrategy;

/**
 * Class StaticWaitStrategyFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class StaticWaitStrategyFactory
{
    /**
     * Make a new StaticWaitStrategy strategy
     *
     * @param int $secondsPerInterval
     *
     * @return StaticWaitStrategy
     */
    public function make($secondsPerInterval = StaticWaitStrategy::DEFAULT_SECONDS_PER_INTERVAL)
    {
        return new StaticWaitStrategy($secondsPerInterval);
    }
} 
