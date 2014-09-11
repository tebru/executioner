<?php
/**
 * File ExceptionDelegator.php
 */

namespace Tebru\Executioner;

use Exception;

/**
 * Interface ExceptionDelegator
 *
 * Use this interface to create new exception delgators.  These are used
 * to run code based on a given exception.
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface ExceptionDelegator
{
    /**
     * Run code based on exception
     *
     * @param Exception $exception
     *
     * @return mixed
     */
    public function delegate(Exception $exception);
}
