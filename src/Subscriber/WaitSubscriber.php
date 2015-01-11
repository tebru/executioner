<?php
/**
 * File WaitSubscriber.php 
 */

namespace Tebru\Executioner\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tebru\Executioner\Event\FailedAttemptEvent;
use Tebru\Executioner\Executor;
use Tebru\Executioner\Strategy\ExponentialBackoffStrategy;
use Tebru\Executioner\Strategy\WaitStrategy;

/**
 * Class WaitSubscriber
 *
 * @author Nate Brunette <n@tebru.net>
 */
class WaitSubscriber implements EventSubscriberInterface
{
    /**
     * @var WaitStrategy $waitStrategy
     */
    private $waitStrategy;

    /**
     * Constructor
     *
     * Will create an ExponentialBackoffStrategy by default
     *
     * @param WaitStrategy $waitStrategy
     */
    public function __construct(WaitStrategy $waitStrategy = null)
    {
        if (null === $waitStrategy) {
            $waitStrategy = new ExponentialBackoffStrategy();
        }

        $this->waitStrategy = $waitStrategy;
    }
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            Executor::EVENT_FAILED_ATTEMPT => 'onFailedAttempt',
        ];
    }

    /**
     * Wait if we've failed
     *
     * @param FailedAttemptEvent $event
     *
     */
    public function onFailedAttempt(FailedAttemptEvent $event)
    {
        $this->waitStrategy->wait();
    }
}
