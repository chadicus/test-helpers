<?php

namespace ChadicusTest;

use Chadicus\ArrayAssertsTrait;

/**
 * @coversDefaultClass \Chadicus\ArrayAssertsTrait
 * @covers ::<private>
 */
final class ArrayAssertsTraitTest extends \PHPUnit\Framework\TestCase
{
    use ArrayAssertsTrait;

    /**
     * Verify basic behavior of assertSameArray().
     *
     * @test
     * @covers ::assertSameArray
     *
     * @return void
     */
    public function sameArray()
    {
        $expected = [
            'foo' => 1,
            'bar' => 2,
        ];

        $actual = [
            'bar' => 2,
            'foo' => 1,
        ];

        $this->assertSameArray($expected, $actual);
    }

    /**
     * Verify behavior of assertSameArray() with missing keys in $actual.
     *
     * @test
     * @covers ::assertSameArray
     *
     * @return void
     */
    public function sameArrayMissingKeys()
    {
        $expected = [
            'foo' => 1,
            'bar' => 2,
            'baz' => 3,
        ];

        $actual = [
            'bar' => 2,
            'foo' => 1,
        ];

        try {
            $this->assertSameArray($expected, $actual);
        } catch (\PHPUnit\Framework\AssertionFailedError $e) {
            $this->assertStringContainsString('$actual array is missing 1 keys: baz', $e->getMessage());
            return;
        }

        $this->fail();
    }

    /**
     * Verify behavior of assertSameArray() with unexpected keys in $actual.
     *
     * @test
     * @covers ::assertSameArray
     *
     * @return void
     */
    public function sameArrayUnexpectedKeys()
    {
        $expected = [
            'foo' => 1,
            'bar' => 2,
        ];

        $actual = [
            'bar' => 2,
            'foo' => 1,
            'baz' => 3,
        ];

        try {
            $this->assertSameArray($expected, $actual);
        } catch (\PHPUnit\Framework\AssertionFailedError $e) {
            $this->assertStringContainsString('$actual array contains 1 unexpected keys: baz', $e->getMessage());
            return;
        }

        $this->fail();
    }

    /**
     * Verify behavior of assertSameArray() nested array.
     *
     * @test
     * @covers ::assertSameArray
     *
     * @return void
     */
    public function sameArrayNestedArray()
    {
        $expected = [
            'foo' => 1,
            'bar' => 2,
            'sub' => [
               'sub1' => 'foo',
               'sub2' => 'bar',
            ],
        ];

        $actual = [
            'bar' => 2,
            'sub' => [
               'sub2' => 'bar',
               'sub1' => 'foo',
            ],
            'foo' => 1,
        ];

        $this->assertSameArray($expected, $actual);
    }

    /**
     * Verify behavior of assertSameArray() with not same nested array.
     *
     * @test
     * @covers ::assertSameArray
     *
     * @return void
     */
    public function sameArrayNotSameNestedArray()
    {
        $expected = [
            'foo' => 1,
            'bar' => 2,
            'sub' => [
               'sub1' => 'bar',
               'sub2' => 'foo',
            ],
        ];

        $actual = [
            'bar' => 2,
            'sub' => [
               'sub2' => 'bar',
               'sub1' => 'foo',
            ],
            'foo' => 1,
        ];

        try {
            $this->assertSameArray($expected, $actual);
        } catch (\PHPUnit\Framework\AssertionFailedError $e) {
            $this->assertStringContainsString(
                "sub.sub1 value is not correct expected 'bar'\nfound 'foo'",
                $e->getMessage()
            );
            return;
        }

        $this->fail();
    }
}
