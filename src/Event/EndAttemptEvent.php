<?php
/**
 * File EndAttemptEvent.php
 */

namespace Tebru\Executioner\Event;

use Exception;

/**
 * Class EndAttemptEvent
 *
 * Dispatched when we have stopped retrying
 *
 * @author Nate Brunette <n@tebru.net>
 */
class EndAttemptEvent extends ExecutionEvent
{
    /**
     * @var Exception $exception
     */
    private $exception;

    /**
     * Construct
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
    }}
