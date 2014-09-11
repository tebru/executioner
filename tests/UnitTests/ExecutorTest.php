<?php
/**
 * File ExecutorTest.php
 */

namespace Tebru\Executioner\Test;

use Exception;
use Mockery;
use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Attemptor;
use Tebru\Executioner\Closure\NullClosure;
use Tebru\Executioner\Delegate\DelegateException;
use Tebru\Executioner\Executor;
use Tebru\Executioner\Strategy\TerminationStrategy;
use Tebru\Executioner\Strategy\WaitStrategy;
use Tebru\Executioner\Logger\ExceptionLogger;
use UnexpectedValueException;

/**
 * Class ExecutorTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ExecutorTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testNoException()
    {
        $attemptor = $this->mockAttemptor();
        $attemptor->shouldReceive('attemptOperation')->once()->andReturnNull();
        $termination = $this->mockTerminationStrategy();
        $termination->shouldReceive('start')->once();

        $executor = new Executor($this->mockWaitStrategy(), $termination);
        $executor->execute($attemptor, $this->mockExceptionLogger());
    }

    public function testExceptionNoStrategy()
    {
        $attemptor = $this->mockAttemptor();
        $attemptor->shouldReceive('attemptOperation')->once()->andThrow(new Exception());
        $attemptor->shouldReceive('getFailureExceptions')->once()->andReturn([]);
        $attemptor->shouldReceive('getRetryableExceptions')->once()->andReturn([]);
        $attemptor->shouldReceive('exitOperation')->once();

        $termination = $this->mockTerminationStrategy();
        $termination->shouldReceive('start')->once();
        $termination->shouldReceive('addAttempt')->once();
        $termination->shouldReceive('hasFinished')->once()->andReturn(true);
        $termination->shouldReceive('getStartedTime')->once();
        $termination->shouldReceive('getAttempts')->once();

        $logger = $this->mockExceptionLogger();
        $logger->shouldReceive('log')->once()->withAnyArgs();
        $logger->shouldReceive('getLogLevel')->once();
        $logger->shouldReceive('getErrorMessage')->once();

        $executor = new Executor($this->mockWaitStrategy(), $termination);
        $executor->execute($attemptor, $logger);
    }

    public function testExceptionOneRetry()
    {
        $attemptor = $this->mockAttemptor();
        $attemptor->shouldReceive('attemptOperation')->twice()->andThrow(new Exception());
        $attemptor->shouldReceive('getFailureExceptions')->twice()->andReturn([]);
        $attemptor->shouldReceive('getRetryableExceptions')->twice()->andReturn([]);
        $attemptor->shouldReceive('exitOperation')->once();

        $wait = $this->mockWaitStrategy();
        $wait->shouldReceive('wait')->once();

        $termination = $this->mockTerminationStrategy();
        $termination->shouldReceive('start')->once();
        $termination->shouldReceive('addAttempt')->twice();
        $termination->shouldReceive('hasFinished')->twice()->andReturnValues([false, true]);
        $termination->shouldReceive('getStartedTime')->once();
        $termination->shouldReceive('getAttempts')->twice();

        $logger = $this->mockExceptionLogger();
        $logger->shouldReceive('info')->once()->withAnyArgs();
        $logger->shouldReceive('log')->once()->withAnyArgs();
        $logger->shouldReceive('getLogLevel')->once();
        $logger->shouldReceive('getErrorMessage')->twice();

        $executor = new Executor($wait, $termination);
        $executor->execute($attemptor, $logger);
    }

    public function testExceptionRetryWithHandler()
    {
        $attemptor = $this->mockAttemptor();
        $attemptor->shouldReceive('attemptOperation')->twice()->andThrow(new Exception());
        $attemptor->shouldReceive('getFailureExceptions')->twice()->andReturn([]);
        $attemptor->shouldReceive('getRetryableExceptions')->twice()->andReturn(
            [new DelegateException(Exception::class, new NullClosure())]
        );
        $attemptor->shouldReceive('exitOperation')->once();

        $wait = $this->mockWaitStrategy();
        $wait->shouldReceive('wait')->once();

        $termination = $this->mockTerminationStrategy();
        $termination->shouldReceive('start')->once();
        $termination->shouldReceive('addAttempt')->twice();
        $termination->shouldReceive('hasFinished')->twice()->andReturnValues([false, true]);
        $termination->shouldReceive('getStartedTime')->once();
        $termination->shouldReceive('getAttempts')->twice();

        $logger = $this->mockExceptionLogger();
        $logger->shouldReceive('info')->once()->withAnyArgs();
        $logger->shouldReceive('log')->once()->withAnyArgs();
        $logger->shouldReceive('getLogLevel')->once();
        $logger->shouldReceive('getErrorMessage')->twice();

        $executor = new Executor($wait, $termination);
        $executor->execute($attemptor, $logger);
    }

    public function testExceptionOneRetryOneSuccess()
    {
        $attemptor = $this->mockAttemptor();
        $attemptor->shouldReceive('attemptOperation')->once()->andThrow(new Exception());
        $attemptor->shouldReceive('attemptOperation')->once()->andReturnNull();
        $attemptor->shouldReceive('getFailureExceptions')->once()->andReturn([]);
        $attemptor->shouldReceive('getRetryableExceptions')->once()->andReturn([]);

        $wait = $this->mockWaitStrategy();
        $wait->shouldReceive('wait')->once();

        $termination = $this->mockTerminationStrategy();
        $termination->shouldReceive('start')->once();
        $termination->shouldReceive('addAttempt')->once();
        $termination->shouldReceive('hasFinished')->once()->andReturn(false);
        $termination->shouldReceive('getAttempts')->once();

        $logger = $this->mockExceptionLogger();
        $logger->shouldReceive('info')->once()->withAnyArgs();
        $logger->shouldReceive('getErrorMessage')->once();

        $executor = new Executor($wait, $termination);
        $executor->execute($attemptor, $logger);
    }

    public function testExceptionSkipRetry()
    {
        $attemptor = $this->mockAttemptor();
        $attemptor->shouldReceive('attemptOperation')->once()->andThrow(new Exception());
        $attemptor->shouldReceive('getFailureExceptions')->once()->andReturn(
            [new DelegateException(Exception::class, new NullClosure())]
        );

        $termination = $this->mockTerminationStrategy();
        $termination->shouldReceive('start')->once()->andReturnNull();
        $termination->shouldReceive('addAttempt')->once();

        $logger = $this->mockExceptionLogger();
        $logger->shouldReceive('error')->once()->withAnyArgs();
        $logger->shouldReceive('getErrorMessage')->once();

        $executor = new Executor($this->mockWaitStrategy(), $termination);
        $executor->execute($attemptor, $logger);
    }

    /**
     * @expectedException \Exception
     */
    public function testExceptionNoValidHandlersThrowsException()
    {
        $attemptor = $this->mockAttemptor();
        $attemptor->shouldReceive('attemptOperation')->once()->andThrow(new Exception());
        $attemptor->shouldReceive('getFailureExceptions')->once()->andReturn(
            [new DelegateException(UnexpectedValueException::class, new NullClosure())]
        );
        $attemptor->shouldReceive('getRetryableExceptions')->once()->andReturn(
            [new DelegateException(UnexpectedValueException::class, new NullClosure())]
        );

        $termination = $this->mockTerminationStrategy();
        $termination->shouldReceive('start')->once();
        $termination->shouldReceive('addAttempt')->once();

        $logger = $this->mockExceptionLogger();
        $logger->shouldReceive('error')->once()->withAnyArgs();
        $logger->shouldReceive('getErrorMessage')->once();

        $executor = new Executor($this->mockWaitStrategy(), $termination);
        $executor->execute($attemptor, $logger);
    }

    /**
     * @expectedException \Tebru\Executioner\Exception\TypeMismatchException
     */
    public function testInvalidArrayThrowsException()
    {
        $attemptor = $this->mockAttemptor();
        $attemptor->shouldReceive('attemptOperation')->once()->andThrow(new Exception());
        $attemptor->shouldReceive('getFailureExceptions')->once()->andReturn([new NullClosure()]);

        $termination = $this->mockTerminationStrategy();
        $termination->shouldReceive('start')->once();
        $termination->shouldReceive('addAttempt')->once();

        $executor = new Executor($this->mockWaitStrategy(), $termination);
        $executor->execute($attemptor, $this->mockExceptionLogger());
    }

    private function mockAttemptor()
    {
        return Mockery::mock(Attemptor::class);
    }

    private function mockExceptionLogger()
    {
        return Mockery::mock(ExceptionLogger::class);
    }

    private function mockWaitStrategy()
    {
        return Mockery::mock(WaitStrategy::class);
    }

    private function mockTerminationStrategy()
    {
        return Mockery::mock(TerminationStrategy::class);
    }
} 
