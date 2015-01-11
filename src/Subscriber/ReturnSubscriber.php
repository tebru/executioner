<?php
/**
 * File ReturnSubscriber.php 
 */

namespace Tebru\Executioner\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tebru\Executioner\Event\AfterAttemptEvent;
use Tebru\Executioner\Exception\ReturnException;
use Tebru\Executioner\Executor;

/**
 * Class ReturnSubscriber
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ReturnSubscriber implements EventSubscriberInterface
{

    /**
     * @var array $returns
     */
    private $returns;

    /**
     * Constructor
     *
     * @param array $returns
     */
    public function __construct(array $returns = [])
    {
        $this->returns = $returns;
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
            Executor::EVENT_AFTER_ATTEMPT => 'onAfterAttempt',
        ];
    }

    /**
     * Check if we have a return we should retry on, and throw an exception if so
     *
     * @param AfterAttemptEvent $event
     */
    public function onAfterAttempt(AfterAttemptEvent $event)
    {
        if (in_array($event->getResult(), $this->returns)) {
            throw new ReturnException('Retrying on return, converting to exception');
        }
    }
}
