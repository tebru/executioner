<?php
/**
 * File MockImmediatelyFailableException.php
 */

namespace Tebru\Executioner\Test\Mock;

use Tebru\Executioner\Attemptor\ImmediatelyFailable;
use Tebru\Executioner\Attemptor\Invokeable;

/**
 * Interface MockImmediatelyFailableException
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface MockImmediatelyFailableException extends Invokeable, ImmediatelyFailable
{
}
