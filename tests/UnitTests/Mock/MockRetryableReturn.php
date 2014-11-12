<?php
/**
 * File MockRetryableReturn.php 
 */

namespace Tebru\Executioner\Test\Mock;

use Tebru\Executioner\Attemptor\Invokeable;
use Tebru\Executioner\Attemptor\ReturnRetryable;

/**
 * Interface MockRetryableReturn
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface MockRetryableReturn extends Invokeable, ReturnRetryable
{
}
