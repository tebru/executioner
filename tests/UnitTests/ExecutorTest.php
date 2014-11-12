<?php
/**
 * File ExecutorTest.php
 */

namespace Tebru\Executioner\Test;

use Exception;
use Mockery;
use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Attemptor;
use Tebru\Executioner\Attemptor\Invokeable;
use Tebru\Executioner\Delegate\ExceptionDelegate;
use Tebru\Executioner\Executor;
use Tebru\Executioner\Strategy\AttemptAwareTerminationStrategy;
use Tebru\Executioner\Strategy\TimeAwareTerminationStrategy;
use Tebru\Executioner\Strategy\WaitStrategy;
use Tebru\Executioner\Logger\ExceptionLogger;
use Tebru\Executioner\Test\Mock\MockImmediatelyFailableException;
use Tebru\Executioner\Test\Mock\MockRetryableException;
use Tebru\Executioner\Test\Mock\MockRetryableReturn;

/**
 * Class ExecutorTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ExecutorTest extends PHPUnit_Framework_TestCase
{
    const METHOD_INVOKE = '__invoke';
    const METHOD_RETRYABLE_RETURNS = 'getRetryableReturns';
    const METHOD_RETRYABLE_EXCEPTIONS = 'getRetryableExceptions';
    const METHOD_IMMEDIATELY_FAILABLE_EXCEPTIONS = 'getImmediatelyFailableExceptions';
    const METHOD_LOG = 'log';
    const METHOD_ERROR_MESSAGE = 'getErrorMessage';
    const METHOD_WAIT = 'wait';
    const METHOD_RESET = 'reset';
    const METHOD_ADD_ATTEMPT = 'addAttempt';
    const METHOD_GET_ATTEMPTS = 'getAttempts';
    const METHOD_HAS_FINISHED = 'hasFinished';
    const METHOD_GET_LOG_LEVEL = 'getLogLevel';
    const METHOD_GET_STARTED_TIME = 'getStartedTime';
    const METHOD_GET_CURRENT_TIME = 'getCurrentTime';

    public function tearDown()
    {
        Mockery::close();
    }

    public function testSimpleNoException()
    {
        $executor = new Executor();
        $result = $executor->execute(function () { return 'test'; });

        $this->assertEquals('test', $result);
    }

    public function testSimpleAttemptor()
    {
        $attemptor = Mockery::mock(Invokeable::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(1)->andReturn('test');

        $executor = new Executor();
        $result = $executor->execute($attemptor);

        $this->assertEquals('test', $result);
    }

    public function testCanSetRetryableException()
    {
        $attemptor = Mockery::mock(Invokeable::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(1)->andThrow(new Exception());
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(1)->andReturn('test');

        $executor = new Executor();
        $executor->setRetryableExceptions([Exception::class]);
        $result = $executor->execute($attemptor);

        $this->assertEquals('test', $result);
    }

    /**
     * @expectedException("\Exception")
     */
    public function testCanSetImmediatelyFailableException()
    {
        $attemptor = Mockery::mock(Invokeable::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(1)->andThrow(new Exception());

        $executor = new Executor();
        $executor->setImmediatelyFailableExceptions([Exception::class]);
        $executor->execute($attemptor);
    }


    public function testWillUseRetryableReturn()
    {
        $attemptor = Mockery::mock(MockRetryableReturn::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(5)->andReturn(false, null, 0, '', '0');
        $attemptor->shouldReceive(self::METHOD_RETRYABLE_RETURNS)->times(5)->andReturn(['', 0, null, false]);

        $executor = new Executor();
        $result = $executor->execute($attemptor);

        $this->assertEquals('0', $result);
    }

    public function testWillUseRetryableReturnWithLog()
    {
        $attemptor = Mockery::mock(MockRetryableReturn::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(5)->andReturn(false, null, 0, '', '0');
        $attemptor->shouldReceive(self::METHOD_RETRYABLE_RETURNS)->times(5)->andReturn(['', 0, null, false]);

        $logger = Mockery::mock(ExceptionLogger::class);
        $logger->shouldReceive(self::METHOD_LOG)->times(4);
        $logger->shouldReceive(self::METHOD_ERROR_MESSAGE)->times(4);

        $executor = new Executor($logger);
        $result = $executor->execute($attemptor);

        $this->assertEquals('0', $result);
    }

    public function testWillUseRetryableException()
    {
        $attemptor = Mockery::mock(MockRetryableException::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(1)->andThrow(new Exception());
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(1)->andReturn('test');
        $attemptor->shouldReceive(self::METHOD_RETRYABLE_EXCEPTIONS)->times(1)->andReturn([new ExceptionDelegate('\Exception')]);

        $executor = new Executor();
        $result = $executor->execute($attemptor);

        $this->assertEquals('test', $result);
    }

    public function testWillUseRetryableExceptionWithLog()
    {
        $attemptor = Mockery::mock(MockRetryableException::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(1)->andThrow(new Exception());
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(1)->andReturn('test');
        $attemptor->shouldReceive(self::METHOD_RETRYABLE_EXCEPTIONS)->times(1)->andReturn([new ExceptionDelegate('\Exception')]);

        $logger = Mockery::mock(ExceptionLogger::class);
        $logger->shouldReceive(self::METHOD_LOG)->times(1);
        $logger->shouldReceive(self::METHOD_ERROR_MESSAGE)->times(1);

        $executor = new Executor($logger);
        $result = $executor->execute($attemptor);

        $this->assertEquals('test', $result);
    }

    /**
     * @expectedException("\Exception")
     */
    public function testImmediatelyFailableException()
    {
        $attemptor = Mockery::mock(MockImmediatelyFailableException::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(1)->andThrow(new Exception());
        $attemptor->shouldReceive(self::METHOD_IMMEDIATELY_FAILABLE_EXCEPTIONS)->times(1)->andReturn([new ExceptionDelegate('\Exception')]);

        $executor = new Executor();
        $executor->execute($attemptor);
    }

    /**
     * @expectedException("\Exception")
     */
    public function testImmediatelyFailableExceptionWithLog()
    {
        $attemptor = Mockery::mock(MockImmediatelyFailableException::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(1)->andThrow(new Exception());
        $attemptor->shouldReceive(self::METHOD_IMMEDIATELY_FAILABLE_EXCEPTIONS)->times(1)->andReturn([new ExceptionDelegate('\Exception')]);

        $logger = Mockery::mock(ExceptionLogger::class);
        $logger->shouldReceive(self::METHOD_LOG)->times(1);
        $logger->shouldReceive(self::METHOD_ERROR_MESSAGE)->times(1);

        $executor = new Executor($logger);
        $executor->execute($attemptor);
    }

    public function testWillUseWaitStrategy()
    {
        $attemptor = Mockery::mock(MockRetryableReturn::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(2)->andReturn(false, 'test');
        $attemptor->shouldReceive(self::METHOD_RETRYABLE_RETURNS)->times(2)->andReturn([false]);

        $waitStrategy = Mockery::mock(WaitStrategy::class);
        $waitStrategy->shouldReceive(self::METHOD_WAIT)->times(1);
        $waitStrategy->shouldReceive(self::METHOD_RESET)->times(1);

        $executor = new Executor();
        $executor->setWaitStrategy($waitStrategy);
        $result = $executor->execute($attemptor);

        $this->assertEquals('test', $result);
    }

    public function testWillUseAttemptAwareTerminationStrategy()
    {
        $attemptor = Mockery::mock(MockRetryableReturn::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(2)->andReturn(false, 'test');
        $attemptor->shouldReceive(self::METHOD_RETRYABLE_RETURNS)->times(2)->andReturn([false]);

        $terminationStrategy = Mockery::mock(AttemptAwareTerminationStrategy::class);
        $terminationStrategy->shouldReceive(self::METHOD_RESET)->times(1);
        $terminationStrategy->shouldReceive(self::METHOD_ADD_ATTEMPT)->times(1);
        $terminationStrategy->shouldReceive(self::METHOD_GET_ATTEMPTS)->times(1);
        $terminationStrategy->shouldReceive(self::METHOD_HAS_FINISHED)->times(1)->andReturn(false);

        $executor = new Executor();
        $executor->setTerminationStrategy($terminationStrategy);
        $result = $executor->execute($attemptor);

        $this->assertEquals('test', $result);
    }

    public function testWillFailAttemptAwareTerminationStrategyWithLog()
    {
        $attemptor = Mockery::mock(MockRetryableReturn::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(2)->andReturn(false);
        $attemptor->shouldReceive(self::METHOD_RETRYABLE_RETURNS)->times(2)->andReturn([false]);

        $terminationStrategy = Mockery::mock(AttemptAwareTerminationStrategy::class);
        $terminationStrategy->shouldReceive(self::METHOD_RESET)->times(1);
        $terminationStrategy->shouldReceive(self::METHOD_ADD_ATTEMPT)->times(2);
        $terminationStrategy->shouldReceive(self::METHOD_GET_ATTEMPTS)->times(2);
        $terminationStrategy->shouldReceive(self::METHOD_HAS_FINISHED)->times(2)->andReturn(false, true);

        $logger = Mockery::mock(ExceptionLogger::class);
        $logger->shouldReceive(self::METHOD_LOG)->times(2);
        $logger->shouldReceive(self::METHOD_ERROR_MESSAGE)->times(2);
        $logger->shouldReceive(self::METHOD_GET_LOG_LEVEL)->times(1);

        $executor = new Executor($logger);
        $executor->setTerminationStrategy($terminationStrategy);
        $result = $executor->execute($attemptor);

        $this->assertEquals(null, $result);
    }

    public function testWillUseTimeAwareTerminationStrategy()
    {
        $attemptor = Mockery::mock(MockRetryableReturn::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(2)->andReturn(false, 'test');
        $attemptor->shouldReceive(self::METHOD_RETRYABLE_RETURNS)->times(2)->andReturn([false]);

        $terminationStrategy = Mockery::mock(TimeAwareTerminationStrategy::class);
        $terminationStrategy->shouldReceive(self::METHOD_RESET)->times(1);
        $terminationStrategy->shouldReceive(self::METHOD_HAS_FINISHED)->times(1)->andReturn(false);

        $executor = new Executor();
        $executor->setTerminationStrategy($terminationStrategy);
        $result = $executor->execute($attemptor);

        $this->assertEquals('test', $result);
    }

    public function testWillFailTimeAwareTerminationStrategyWithLog()
    {
        $attemptor = Mockery::mock(MockRetryableReturn::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(2)->andReturn(false);
        $attemptor->shouldReceive(self::METHOD_RETRYABLE_RETURNS)->times(2)->andReturn([false]);

        $terminationStrategy = Mockery::mock(TimeAwareTerminationStrategy::class);
        $terminationStrategy->shouldReceive(self::METHOD_RESET)->times(1);
        $terminationStrategy->shouldReceive(self::METHOD_HAS_FINISHED)->times(2)->andReturn(false, true);
        $terminationStrategy->shouldReceive(self::METHOD_GET_STARTED_TIME)->times(1)->andReturn(false);
        $terminationStrategy->shouldReceive(self::METHOD_GET_CURRENT_TIME)->times(1)->andReturn(false);

        $logger = Mockery::mock(ExceptionLogger::class);
        $logger->shouldReceive(self::METHOD_LOG)->times(2);
        $logger->shouldReceive(self::METHOD_ERROR_MESSAGE)->times(2);
        $logger->shouldReceive(self::METHOD_GET_LOG_LEVEL)->times(1);

        $executor = new Executor($logger);
        $executor->setTerminationStrategy($terminationStrategy);
        $result = $executor->execute($attemptor);

        $this->assertEquals(null, $result);
    }

    /**
     * @expectedException \Exception
     */
    public function testExceptionNoValidHandlersThrowsException()
    {
        $attemptor = Mockery::mock(Attemptor::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(1)->andThrow(new Exception());
        $attemptor->shouldReceive(self::METHOD_IMMEDIATELY_FAILABLE_EXCEPTIONS)->times(1)->andReturn([]);
        $attemptor->shouldReceive(self::METHOD_RETRYABLE_EXCEPTIONS)->times(1)->andReturn([new ExceptionDelegate('\UnexpectedValueException')]);

        $executor = new Executor();
        $executor->execute($attemptor);
    }

    /**
     * @expectedException \Exception
     */
    public function testExceptionNoValidHandlersThrowsExceptionWithLog()
    {
        $attemptor = Mockery::mock(Attemptor::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->times(1)->andThrow(new Exception());
        $attemptor->shouldReceive(self::METHOD_IMMEDIATELY_FAILABLE_EXCEPTIONS)->times(1)->andReturn([]);
        $attemptor->shouldReceive(self::METHOD_RETRYABLE_EXCEPTIONS)->times(1)->andReturn([new ExceptionDelegate('\UnexpectedValueException')]);

        $logger = Mockery::mock(ExceptionLogger::class);
        $logger->shouldReceive(self::METHOD_LOG)->times(1);
        $logger->shouldReceive(self::METHOD_ERROR_MESSAGE)->times(1);

        $executor = new Executor($logger);
        $executor->execute($attemptor);
    }

    /**
     * @expectedException \Tebru\Executioner\Exception\TypeMismatchException
     */
    public function testInvalidArrayThrowsException()
    {
        $attemptor = Mockery::mock(MockRetryableException::class);
        $attemptor->shouldReceive(self::METHOD_INVOKE)->once()->andThrow(new Exception());
        $attemptor->shouldReceive(self::METHOD_RETRYABLE_EXCEPTIONS)->once()->andReturn([null]);

        $executor = new Executor();
        $executor->execute($attemptor);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testWillThrowExceptionWithoutAttemptor()
    {
        $executor = new Executor();
        $executor->execute();
    }
}
