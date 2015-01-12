<?php
/**
 * File ExecutorFactory.php
 */

namespace Tebru\Executioner\Factory;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @param EventDispatcherInterface $eventDispatcher
     * @return Executor
     */
    public function make(EventDispatcherInterface $eventDispatcher = null) {
        return new Executor($eventDispatcher);
    }
} 
