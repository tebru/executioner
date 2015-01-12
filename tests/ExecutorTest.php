<?php
/**
 * File ExecutorTest.php
 */

namespace Tebru\Executioner\Test;

use Akamon\MockeryCallableMock\MockeryCallableMock;
use Exception;
use Mockery;
use PHPUnit_Framework_TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Tebru\Executioner\Executor;
use Tebru\Executioner\Subscriber\LoggerSubscriber;

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

    public function testWillPass()
    {
        $executor = new Executor();
        $result = $executor->execute(1, function () { return true; });
        $this->assertTrue($result);
    }

    public function testFailPass()
    {
        $callable = new MockeryCallableMock();
        $callable->shouldBeCalled()->times(1)->withNoArgs()->andThrow(new Exception());
        $callable->shouldBeCalled()->times(1)->withNoArgs()->andReturn(true);

        $executor = new Executor();
        $result = $executor->execute(2, $callable);

        $this->assertTrue($result);
    }

    /**
     * @expectedException \Tebru\Executioner\Exception\FailedException
     */
    public function testNeverPass()
    {
        $callable = new MockeryCallableMock();
        $callable->shouldBeCalled()->times(3)->withNoArgs()->andThrow(new Exception());

        $executor = new Executor();
        $executor->execute(2, $callable);
    }

    /**
     * @expectedException \Tebru\Executioner\Exception\FailedException
     */
    public function testWithLogger()
    {
        $callable = new MockeryCallableMock();
        $exception = new Exception();
        $callable->shouldBeCalled()->times(1)->withNoArgs()->andThrow($exception);
        $callable->shouldBeCalled()->times(1)->withNoArgs()->andReturn(true);

        $logger = Mockery::mock(LoggerInterface::class);
        $dispatchException = new Exception();

        $logger->shouldReceive('info')->times(1)->with('Attempting "test" with 1 attempts to go. (uq)');
        $logger->shouldReceive('info')->times(1)->with('Attempting "test" with 0 attempts to go. (uq)');
        $logger->shouldReceive('info')->times(1)->with('Completed attempt for "test" (uq)', ['result' => true])->andThrow($dispatchException);
        $logger->shouldReceive('notice')->times(1)->with('Failed attempt for "test", retrying. 0 attempts remaining (uq)', ['exception' => $exception]);
        $logger->shouldReceive('error')->times(1)->with('Could not complete "test" (uq)', ['exception' => $dispatchException]);

        $loggerSubscriber = new LoggerSubscriber('test', $logger, 'uq');

        $dispatcher = Mockery::mock(EventDispatcher::class);
        $dispatcher->makePartial();
        $dispatcher->shouldReceive('dispatch')->times(5)->passthru();
        $dispatcher->shouldReceive('addSubscriber')->times(1)->with($loggerSubscriber)->passthru();

        $executor = new Executor();
        $executor->setDispatcher($dispatcher);
        $executor->addSubscriber($loggerSubscriber);
        $executor->execute(1, $callable);
    }
}
