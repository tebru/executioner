<?php
/**
 * File NullWaitFactory.php
 */

namespace Tebru\Executioner\Factory\WaitStrategy;

use Tebru\Executioner\Strategy\Wait\NullWait;

/**
 * Class NullWaitFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class NullWaitFactory
{
    /**
     * Make a new NullWait strategy
     *
     * @return NullWait
     */
    public function make()
    {
        return new NullWait();
    }
} 
