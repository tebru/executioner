<?php
/**
 * File NullWaitStrategyFactory.php
 */

namespace Tebru\Executioner\Factory\Wait;

use Tebru\Executioner\Strategy\Wait\NullWaitStrategy;

/**
 * Class NullWaitStrategyFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class NullWaitStrategyFactory
{
    /**
     * Make a new NullWaitStrategy strategy
     *
     * @return NullWaitStrategy
     */
    public function make()
    {
        return new NullWaitStrategy();
    }
} 
