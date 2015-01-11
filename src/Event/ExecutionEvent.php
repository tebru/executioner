<?php
/**
 * File ExecutionEvent.php 
 */

namespace Tebru\Executioner\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class ExecutionEvent
 *
 * Parent event -- each event should have access to the number of attempts
 *
 * @author Nate Brunette <n@tebru.net>
 */
abstract class ExecutionEvent extends Event
{
    /**
     * @var int $attempts
     */
    private $attempts;

    /**
     * Constructor
     *
     * @param int $attempts
     */
    public function __construct($attempts)
    {
        $this->attempts = (int)$attempts;
    }

    /**
     * @return int
     */
    public function getAttempts()
    {
        return $this->attempts;
    }

    /**
     * @param int $attempts
     * @return $this
     */
    public function setAttempts($attempts)
    {
        $this->attempts = (int)$attempts;
        return $this;
    }

} 
