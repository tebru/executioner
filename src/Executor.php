<?php
/**
 * File Executor.php
 */

namespace Tebru\Executioner;

use Exception;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tebru\Executioner\Event\AfterAttemptEvent;
use Tebru\Executioner\Event\BeforeAttemptEvent;
use Tebru\Executioner\Event\EndAttemptEvent;
use Tebru\Executioner\Event\FailedAttemptEvent;
use Tebru\Executioner\Exception\FailedException;
use Tebru\Executioner\Exception\InvalidArgumentException;

/**
 * Class Executor
 *
 * Wrapper that handles the executing and retrying of an operation
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Executor
{
    /**#@
     * Events
     */
    const EVENT_BEFORE_ATTEMPT = 'beforeAttempt';
    const EVENT_AFTER_ATTEMPT = 'afterAttempt';
    const EVENT_FAILED_ATTEMPT = 'failedAttempt';
    const EVENT_END_ATTEMPT = 'endAttempt';
    /**#@-*/

    /**
     * @var array $events
     */
    static public  $events = [
        self::EVENT_BEFORE_ATTEMPT,
        self::EVENT_AFTER_ATTEMPT,
        self::EVENT_FAILED_ATTEMPT,
        self::EVENT_END_ATTEMPT,
    ];

    /**
     * @var EventDispatcherInterface $eventDispatcher
     */
    private $eventDispatcher;

    /**
     * Constructor
     *
     * Creates an EventDispatcher by default
     *
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher = null) {
        if (null === $eventDispatcher) {
            $eventDispatcher = new EventDispatcher();
        }

        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Try to execute code
     *
     * @param int $attempts
     * @param callable $attempter The code getting attempted
     * @return mixed
     * @throws FailedException If we can't continue retrying
     */
    public function execute($attempts, callable $attempter)
    {
        try {
            $beforeAttemptEvent = new BeforeAttemptEvent($attempts);
            $this->eventDispatcher->dispatch(self::EVENT_BEFORE_ATTEMPT, $beforeAttemptEvent);

            // make an attempt
            $result = $attempter();

            $afterAttemptEvent = new AfterAttemptEvent($attempts - 1, $result);
            $this->eventDispatcher->dispatch(self::EVENT_AFTER_ATTEMPT, $afterAttemptEvent);
        } catch (Exception $exception) {
            // if we're out of tries, quit
            if (0 === $attempts) {
                $endEvent = new EndAttemptEvent($attempts, $exception);
                $this->eventDispatcher->dispatch(self::EVENT_END_ATTEMPT, $endEvent);

                throw new FailedException('Could not complete execution', 0, $exception);
            }

            $attempts--;

            $failedAttemptEvent = new FailedAttemptEvent($attempts, $exception);
            $this->eventDispatcher->dispatch(self::EVENT_FAILED_ATTEMPT, $failedAttemptEvent);

            // recursion
            return $this->execute($attempts, $attempter);
        }

        return $result;
    }

    /**
     * Add a listener
     *
     * @param string $name
     * @param callable $listener
     * @param int $priority
     * @return $this
     */
    public function addListener($name, callable $listener, $priority = 0)
    {
        if (!in_array($name, self::$events)) {
            throw new InvalidArgumentException(sprintf('Event %s does not exist', $name));
        }

        $this->eventDispatcher->addListener($name, $listener, $priority);

        return $this;
    }

    /**
     * Add a listener to the before attempt event
     *
     * @param callable $listener
     * @param int $priority
     * @return $this
     */
    public function onBeforeAttempt(callable $listener, $priority = 0)
    {
        $this->addListener(self::EVENT_BEFORE_ATTEMPT, $listener, $priority);

        return $this;
    }

    /**
     * Add a listener to the after attempt event
     *
     * @param callable $listener
     * @param int $priority
     * @return $this
     */
    public function onAfterAttempt(callable $listener, $priority = 0)
    {
        $this->addListener(self::EVENT_AFTER_ATTEMPT, $listener, $priority);

        return $this;
    }

    /**
     * Add a listener to the failed attempt event
     *
     * @param callable $listener
     * @param int $priority
     * @return $this
     */
    public function onFailedAttempt(callable $listener, $priority = 0)
    {
        $this->addListener(self::EVENT_FAILED_ATTEMPT, $listener, $priority);

        return $this;
    }

    /**
     * Add a listener to the end attempt event
     *
     * @param callable $listener
     * @param int $priority
     *
     * @return $this
     */
    public function onEndAttempt(callable $listener, $priority = 0)
    {
        $this->addListener(self::EVENT_END_ATTEMPT, $listener, $priority);

        return $this;
    }

    /**
     * Add a subscriber
     *
     * @param EventSubscriberInterface $subscriber
     *
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->eventDispatcher->addSubscriber($subscriber);
    }

    /**
     * Get the dispatcher
     *
     * @return EventDispatcher|EventDispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->eventDispatcher;
    }
}
