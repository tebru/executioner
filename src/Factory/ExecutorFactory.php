<?php
/**
 * File ExecutorFactory.php
 */

namespace Tebru\Executioner\Factory;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Tebru\Executioner\Executor;

/**
 * Class ExecutorFactory
 *
 * Creates an Executor
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ExecutorFactory
{
    /**
     * Make an Executor
     *
     * @param EventDispatcher $eventDispatcher
     * @return Executor
     */
    public static function make(EventDispatcher $eventDispatcher = null) {
        return new Executor($eventDispatcher);
    }
} 
