<?php
/**
 * File SingleAttemptFactory.php 
 */

namespace Tebru\Executioner\Factory\TerminationStrategy;

use Tebru\Executioner\Strategy\Termination\SingleAttemptStrategy;

/**
 * Class SingleAttemptFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class SingleAttemptFactory
{
    /**
     * Make a new SingleAttemptStrategy
     *
     * @return SingleAttemptStrategy
     */
    public function make()
    {
        return new SingleAttemptStrategy();
    }
} 
