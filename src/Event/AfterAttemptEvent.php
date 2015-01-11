<?php
/**
 * File AfterAttemptEvent.php 
 */

namespace Tebru\Executioner\Event;

/**
 * Class AfterAttemptEvent
 *
 * Use this event to access the returned value after an attempt
 *
 * @see \Tebru\Executioner\Subscriber\ReturnSubscriber
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AfterAttemptEvent extends ExecutionEvent
{
    /**
     * The result of an attempt
     *
     * @var mixed $result
     */
    private $result;

    /**
     * Constructor
     *
     * @param int $attempts
     * @param mixed $result
     */
    public function __construct($attempts, $result)
    {
        parent::__construct($attempts);

        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     * @return $this
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }
} 
