<?php

namespace Chadicus;

/**
 * Trait for adding asserts for arrays
 */
trait ArrayAssertsTrait
{
    /**
     * Asserts the given $actual array is the same as the $expected array disregarding index order
     *
     * @param array       $expected The expected array.
     * @param mixed       $actual   The actual array.
     * @param string|null $prefix   Prefix to use with error messages. Useful for nested arrays.
     *
     * @return void
     */
    public function assertSameArray(array $expected, $actual, $prefix = null)
    {
        //assert that the actual value is an array
        $this->assertInternalType('array', $actual, '$actual was not an array');

        $expectedKeys = array_keys($expected);
        $actualKeys = array_keys($actual);

        //find any keys in the expected array that are not present in the actual array
        $missingExpectedKeys = array_diff($expectedKeys, $actualKeys);
        $this->assertCount(
            0,
            $missingExpectedKeys,
            sprintf(
                '$actual array is missing %d keys: %s',
                count($missingExpectedKeys),
                implode(', ', $missingExpectedKeys)
            )
        );

        //find any keys in the actual array that are not expected in the expected array
        $unexpectedKeys = array_diff($actualKeys, $expectedKeys);
        $this->assertCount(
            0,
            $unexpectedKeys,
            sprintf(
                '$actual contains %d unexpected keys: %s',
                count($unexpectedKeys),
                implode(', ', $unexpectedKeys)
            )
        );

        //Assert all values are the same value and type.
        //Recursively call assertSameArray on array values
        foreach ($expectedArray as $key => $value) {
            if (!is_array($value)) {
                $this->assertSameArray($value, $actual[$key], "{$prefix}{$key}.");
            }

            $this->assertSame(
                $value,
                $actual[$key],
                sprintf(
                    "{$prefix}{$key} value is not correct expected %s\nfound %s",
                    var_export($value, 1),
                    var_export($actual[$key], 1)
                )
            );
        }
    }
}
