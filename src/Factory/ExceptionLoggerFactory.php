<?php
/**
 * File ExceptionLoggerFactory.php
 */

namespace Tebru\Executioner\Factory;
use Psr\Log\LoggerInterface;
use Tebru\Executioner\Logger\ExceptionLogger;

/**
 * Class ExceptionLoggerFactory
 *
 * @author Nate Brunette <nbrunett@nerdery.com>
 */
class ExceptionLoggerFactory
{
    /**
     * Create a new ExceptionLogger
     *
     * @param LoggerInterface $logger
     * @param string $logLevel
     * @param string $errorMessage
     *
     * @return ExceptionLogger
     */
    public function make(
        LoggerInterface $logger = null,
        $logLevel = ExceptionLogger::DEFAULT_LOG_LEVEL,
        $errorMessage = ExceptionLogger::DEFAULT_ERROR_MESSAGE
    ) {
        return new ExceptionLogger($logger, $logLevel, $errorMessage);
    }
} 
