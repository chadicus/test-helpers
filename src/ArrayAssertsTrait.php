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
                '$actual array contains %d unexpected keys: %s',
                count($unexpectedKeys),
                implode(', ', $unexpectedKeys)
            )
        );

        //Assert all values are the same value and type.
        //Recursively call assertSameArray on array values
        foreach ($expected as $key => $value) {
            if (is_array($value)) {
                $this->assertSameArray($value, $actual[$key], "{$prefix}{$key}.");
                continue;
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

    /**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * Ensures this method must be provided by classes using this trait.
     *
     * @param integer $expectedCount The expected number of items in $haystack.
     * @param mixed   $haystack      The array, countable or traversable object containing items.
     * @param string  $message       Optional error message to give upon failure.
     *
     * @return void
     */
    abstract public function assertCount($expectedCount, $haystack, $message = '');

    /**
     * Asserts that a variable is of a given type.
     *
     * Ensures this method must be provided by classes using this trait.
     *
     * @param string $expected The expected internal type.
     * @param mixed  $actual   The variable to verify.
     * @param string $message  Optional error message to give upon failure.
     *
     * @return void
     */
    abstract public function assertInternalType($expected, $actual, $message = '');

    /**
     * Asserts that two variables have the same type and value. Used on objects, it asserts that two variables reference
     * the same object.
     *
     * Ensures this method must be provided by classes using this trait.
     *
     * @param string $expected The expected value.
     * @param mixed  $actual   The actual value.
     * @param string $message  Optional error message to give upon failure.
     *
     * @return void
     */
    abstract public function assertSame($expected, $actual, $message = '');
}
