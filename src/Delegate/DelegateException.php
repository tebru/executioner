<?php
/**
 * File DelegateExceptionTest.php
 */

namespace Tebru\Executioner\Delegate;

use Exception;
use Tebru\Executioner\ExceptionDelegator;

/**
 * Class DelegateException
 *
 * Execute a callback for a specific exception
 *
 * @author Nate Brunette <n@tebru.net>
 */
class DelegateException implements ExceptionDelegator
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
    public function __construct($exceptionClass, callable $callback)
    {
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
    public function delegate(Exception $exception)
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
