<?php
/**
 * File StaticWaitFactory.php 
 */

namespace Tebru\Executioner\Factory\WaitStrategy;

use Tebru\Executioner\Strategy\Wait\StaticWait;

/**
 * Class StaticWaitFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class StaticWaitFactory
{
    /**
     * Make a new StaticWait strategy
     *
     * @param int $secondsPerInterval
     *
     * @return StaticWait
     */
    public function make($secondsPerInterval = StaticWait::DEFAULT_SECONDS_PER_INTERVAL)
    {
        return new StaticWait($secondsPerInterval);
    }
} 
