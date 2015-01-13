<?php
namespace Chadicus\Tests;

use \Chadicus\FunctionRegistry;

/**
 * Unit tests for the \Chadicus\FunctionRegistry class.
 *
 * @coversDefaultClass \Chadicus\FunctionRegistry
 */
final class FunctionRegistryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Verify basic behavior of FunctionRegistry.
     *
     * @test
     * @covers ::reset
     * @covers ::set
     * @uses \Chadicus\FunctionRegistry::get
     *
     * @return void
     */
    public function basicUse()
    {
        FunctionRegistry::reset(__NAMESPACE__, ['core']);

        FunctionRegistry::set(
            'extension_loaded',
            function ($name) {
                return true;
            }
        );

        $this->assertTrue(extension_loaded('this could not exist'));
    }

    /**
     * Verify basic functionality of set().
     *
     * @test
     * @covers ::set
     * @covers ::get
     *
     * @return void
     */
    public function setAndGet()
    {
        FunctionRegistry::set('strtolower', '\Chadicus\Tests\FunctionRegistryTest::staticCallable');
        $this->assertSame('\Chadicus\Tests\FunctionRegistryTest::staticCallable', FunctionRegistry::get('strtolower'));

    }

    /**
     * Verify basic functionality of get().
     *
     * @test
     * @covers ::get
     *
     * @return void
     */
    public function get()
    {
        //strtoupper not registered
        $this->assertSame('\strtoupper', FunctionRegistry::get('strtoupper'));
    }

    /**
     * Verify basic functionality of reset().
     *
     * @test
     * @covers ::reset
     * @uses \Chadicus\FunctionRegistry::get
     *
     * @return void
     */
    public function reset()
    {
        foreach (\get_extension_funcs('date') as $name) {
            $this->assertFalse(function_exists(__NAMESPACE__ . "\\{$name}"));
        }

        FunctionRegistry::reset(__NAMESPACE__, ['date']);

        foreach (\get_extension_funcs('date') as $name) {
            $this->assertTrue(function_exists(__NAMESPACE__ . "\\{$name}"));
        }

        // call reset again just to ensure no exceptions thrown
        FunctionRegistry::reset(__NAMESPACE__, ['date']);
    }

    /**
     * Test method to use as a callable in FunctionRegistry.
     *
     * @return true
     */
    public static function staticCallable()
    {
        return true;
    }
}
