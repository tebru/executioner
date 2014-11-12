<?php
/**
 * File Attemptor.php
 */

namespace Tebru\Executioner;

use Tebru\Executioner\Attemptor\ExceptionRetryable;
use Tebru\Executioner\Attemptor\ImmediatelyFailable;
use Tebru\Executioner\Attemptor\Invokeable;
use Tebru\Executioner\Attemptor\ReturnRetryable;

/**
 * Interface Attemptor
 *
 * Attemptor interface uses all available interfaces. May serve as a sample or
 * shortcut to implementing available interfaces.
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface Attemptor extends Invokeable, ExceptionRetryable, ReturnRetryable, ImmediatelyFailable
{
}
