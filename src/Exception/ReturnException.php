<?php
/**
 * File ReturnException.php 
 */

namespace Tebru\Executioner\Exception;

use RuntimeException;

/**
 * Class ReturnException
 *
 * Thrown when we encounter a return value that should be retried on
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ReturnException extends RuntimeException
{

} 
