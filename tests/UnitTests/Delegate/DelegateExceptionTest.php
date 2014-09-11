<?php
/**
 * File DelegateExceptionTest.php
 */

namespace Tebru\Executioner\Test\Delegate;

use Exception;
use Mockery;
use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Closure\NullClosure;
use Tebru\Executioner\Delegate\DelegateException;

/**
 * Class DelegateExceptionTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class DelegateExceptionTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        Mockery::close();
    }

    public function testDelegateTrue()
    {
        $nullClosure = Mockery::mock(new NullClosure());
        $exception = new Exception();

        $nullClosure->shouldReceive('__invoke')->once()->withArgs([$exception]);
        $delegate = new DelegateException('\Exception', $nullClosure);
        $response = $delegate->delegate($exception);

        $this->assertTrue($response);
    }

    public function testDelegateFalse()
    {
        $nullClosure = Mockery::mock(new NullClosure());

        $delegate = new DelegateException('\UnexpectedValueException', $nullClosure);
        $response = $delegate->delegate(new Exception());

        $this->assertFalse($response);
    }
} 
