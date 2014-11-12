<?php
/**
 * File MockRetryableException.php
 */

namespace Tebru\Executioner\Test\Mock;

use Tebru\Executioner\Attemptor\ExceptionRetryable;
use Tebru\Executioner\Attemptor\Invokeable;

/**
 * Interface MockRetryableException
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface MockRetryableException extends Invokeable, ExceptionRetryable
{
}
