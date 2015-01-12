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
     * @var EventDispatcherInterface $eventDispatcher
     */
    private $eventDispatcher;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher = null)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Make an Executor
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @return Executor
     */
    public function make(EventDispatcherInterface $eventDispatcher = null) {
        if (null !== $eventDispatcher) {
            return new Executor($eventDispatcher);
        }

        return new Executor($this->eventDispatcher);
    }
} 
