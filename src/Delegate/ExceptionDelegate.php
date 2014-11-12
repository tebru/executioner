<?php
/**
 * File ExceptionDelegate.php
 */

namespace Tebru\Executioner\Delegate;

use Exception;
use Tebru\Executioner\Closure\NullClosure;
use Tebru\Executioner\ExceptionDelegator;

/**
 * Class ExceptionDelegate
 *
 * Execute a callback for a specific exception
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ExceptionDelegate implements ExceptionDelegator
{
    /**
     * The exception class name
     *
     * @var string $exceptionClass
     */
    private $exceptionClass;

    /**
     * A handler for the exception
     *
     * @var callable $callback
     */
    private $callback;

    /**
     * Constructor
     *
     * @param $exceptionClass
     * @param callable $callback
     */
    public function __construct($exceptionClass, callable $callback = null)
    {
        if (null === $callback) {
            $callback = new NullClosure();
        }

        $this->exceptionClass = $exceptionClass;
        $this->callback = $callback;
    }

    /**
     * If the exception classes match, run the callback
     *
     * @param Exception $exception
     *
     * @return bool True if callback is called, false if not
     */
    public function delegate(Exception $exception = null)
    {
        $exceptionClass = $this->exceptionClass;
        $callback = $this->callback;

        if (!$exception instanceof $exceptionClass) {
            return false;
        }

        $callback($exception);

        return true;
    }
}
