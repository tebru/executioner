<?php
/**
 * File LoggerSubscriber.php 
 */

namespace Tebru\Executioner\Subscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tebru\Executioner\Event\AfterAttemptEvent;
use Tebru\Executioner\Event\BeforeAttemptEvent;
use Tebru\Executioner\Event\EndAttemptEvent;
use Tebru\Executioner\Event\FailedAttemptEvent;
use Tebru\Executioner\Executor;

/**
 * Class LoggerSubscriber
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LoggerSubscriber implements EventSubscriberInterface
{
    /**
     * @var string $name
     */
    private $name;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * @var string $uniqueId
     */
    private $uniqueId;

    /**
     * Constructor
     *
     * @param string $name
     * @param LoggerInterface $logger
     * @param string $uniqueId
     */
    public function __construct($name, LoggerInterface $logger, $uniqueId = null)
    {
        if (null === $uniqueId) {
            $uniqueId = uniqid();
        }
        $this->name = $name;
        $this->logger = $logger;
        $this->uniqueId = $uniqueId;
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
            Executor::EVENT_BEFORE_ATTEMPT => 'onBeforeAttempt',
            Executor::EVENT_AFTER_ATTEMPT => 'onAfterAttempt',
            Executor::EVENT_FAILED_ATTEMPT => 'onFailedAttempt',
            Executor::EVENT_END_ATTEMPT => 'onEndAttempt',
        ];
    }

    /**
     * @param BeforeAttemptEvent $event
     */
    public function onBeforeAttempt(BeforeAttemptEvent $event)
    {
        $this->logger->info(sprintf('Attempting "%s" with %d attempts to go. (%s)', $this->name, $event->getAttempts(), $this->uniqueId));
    }

    /**
     * @param AfterAttemptEvent $event
     */
    public function onAfterAttempt(AfterAttemptEvent $event)
    {
        $this->logger->info(sprintf('Completed attempt for "%s" (%s)', $this->name, $this->uniqueId), ['result' => $event->getResult()]);
    }

    /**
     * @param FailedAttemptEvent $event
     */
    public function onFailedAttempt(FailedAttemptEvent $event)
    {
        $this->logger->notice(
            sprintf('Failed attempt for "%s", retrying. %d attempts remaining (%s)', $this->name, $event->getAttempts(), $this->uniqueId),
            ['exception' => $event->getException()]
        );
    }

    /**
     * @param EndAttemptEvent $event
     */
    public function onEndAttempt(EndAttemptEvent $event)
    {
        $this->logger->error(sprintf('Could not complete "%s" (%s)', $this->name, $this->uniqueId), ['exception' => $event->getException()]);
    }
}
