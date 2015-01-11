<?php
/**
 * File FailedAttemptEvent.php 
 */

namespace Tebru\Executioner\Event;

use Exception;

/**
 * Class FailedAttemptEvent
 *
 * @author Nate Brunette <n@tebru.net>
 */
class FailedAttemptEvent extends ExecutionEvent
{
    /**
     * @var Exception $exception
     */
    private $exception;

    /**
     * Constructor
     *
     * @param int $attempts
     * @param Exception $exception
     */
    public function __construct($attempts, Exception $exception)
    {
        parent::__construct($attempts);

        $this->exception = $exception;
    }

    /**
     * @return Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param Exception $exception
     * @return $this
     */
    public function setException(Exception $exception)
    {
        $this->exception = $exception;

        return $this;
    }
}
