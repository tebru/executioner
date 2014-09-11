<?php
/**
 * File ExceptionLogger.php
 */

namespace Tebru\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class ExceptionLogger
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ExceptionLogger implements LoggerInterface
{
    /**#@+
     * Default logging values
     */
    const DEFAULT_LOG_LEVEL = LogLevel::ERROR;
    const DEFAULT_ERROR_MESSAGE = 'An error occurred.';
    /**#@-*/

    /**
     * PSR compliant Logger
     *
     * @var LoggerInterface logger
     */
    private $logger;

    /**
     * The log level we want to use for exceptions
     *
     * @var string logLevel
     */
    private $logLevel;

    /**
     * The error message we should use on exceptions
     *
     * @var string errorMessage
     */
    private $errorMessage;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     * @param string $logLevel
     * @param string $errorMessage
     */
    public function __construct(
        LoggerInterface $logger = null,
        $logLevel = self::DEFAULT_LOG_LEVEL,
        $errorMessage = self::DEFAULT_ERROR_MESSAGE
    ) {
        $this->logger = $logger;
        $this->logLevel = $logLevel;
        $this->errorMessage = $errorMessage;
    }

    /**
     * Get the logger, creates a PhpErrorLogLogger if the logger is not set
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if (null === $this->logger) {
            $this->logger = new PhpErrorLogLogger();
        }

        return $this->logger;
    }

    /**
     * Set the logger
     *
     * @param LoggerInterface $logger
     *
     * @return $this
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * Get the log level
     *
     * @return string
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * Set the log level
     *
     * @param string $logLevel
     *
     * @return $this
     */
    public function setLogLevel($logLevel)
    {
        $this->logLevel = $logLevel;
        return $this;
    }

    /**
     * Get the error message
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return (string)$this->errorMessage;
    }

    /**
     * Set the error message
     *
     * @param string $errorMessage
     *
     * @return $this
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = (string)$errorMessage;

        return $this;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array())
    {
        $this->getLogger()->emergency($message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array())
    {
        $this->getLogger()->alert($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array())
    {
        $this->getLogger()->critical($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array())
    {
        $this->getLogger()->error($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array())
    {
        $this->getLogger()->warning($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array())
    {
        $this->getLogger()->notice($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array())
    {
        $this->getLogger()->info($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array())
    {
        $this->getLogger()->debug($message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        $this->getLogger()->log($level, $message, $context);
    }
}
