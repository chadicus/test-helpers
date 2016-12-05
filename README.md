# Chadicus Test Helpers

[![Build Status](https://travis-ci.org/chadicus/test-helpers.svg?branch=master)](https://travis-ci.org/chadicus/test-helpers)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/chadicus/test-helpers/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/chadicus/test-helpers/?branch=master)
[![Code Coverage](https://coveralls.io/repos/github/chadicus/test-helpers/badge.svg?branch=master)](https://coveralls.io/github/chadicus/test-helpers?branch=master)

[![Latest Stable Version](https://poser.pugx.org/chadicus/test-helpers/v/stable)](https://packagist.org/packages/chadicus/test-helpers)
[![Latest Unstable Version](https://poser.pugx.org/chadicus/test-helpers/v/unstable)](https://packagist.org/packages/chadicus/test-helpers)
[![License](https://poser.pugx.org/chadicus/test-helpers/license)](https://packagist.org/packages/chadicus/test-helpers)

[![Total Downloads](https://poser.pugx.org/chadicus/test-helpers/downloads)](https://packagist.org/packages/chadicus/test-helpers)
[![Daily Downloads](https://poser.pugx.org/chadicus/test-helpers/d/daily)](https://packagist.org/packages/chadicus/test-helpers)
[![Monthly Downloads](https://poser.pugx.org/chadicus/test-helpers/d/monthly)](https://packagist.org/packages/chadicus/test-helpers)

## Requirements

Test Helpers requires PHP 5.6 (or later).

## Composer
To add the library as a local, per-project dependency use [Composer](http://getcomposer.org)! Simply add a dependency on
`chadicus/test-helpers` to your project's `composer.json` file such as:

```sh
composer require --dev chadicus/test-helpers
```

NOTE: test-helpers should never be used in production. They are meant for testing enviornments only.

## Documentation
PHP docs for the project can be found [here](http://chadicus.github.io/test-helpers).

## Contact
Developers may be contacted at:

 * [Pull Requests](https://github.com/chadicus/test-helpers/pulls)
 * [Issues](https://github.com/chadicus/test-helpers/issues)

## Project Build
With a checkout of the code get [Composer](http://getcomposer.org) in your PATH and run:

```sh
composer install
./vendor/bin/phpunit
./vendor/bin/phpcs --standard=./vendor/chadicus/coding-standard/Chadicus -n src
```
# \Chadicus\FunctionRegistry

Some internal PHP functions are documented to return certain values on failure. If you're a meticulous programmer you want to account for these return values in your code and respond to them accordingly.
```php
class MyClass
{
    public function doSomething()
    {
        $curl = curl_init();
        if ($curl === false) {
            throw new \Exception('curl_init() failed');
        }

        //do something with $curl ...
    }
}
```

A meticulous programmer may also want to ensure their unit test code coverage is 100%.

One way to accomplish this is to use @codeCoverageIgnore annotations
```php
class MyClass
{
    public function doSomething()
    {
        $curl = curl_init();
        if ($curl === false) {
            //@codeCoverageIgnoreStart
            throw new \Exception('curl_init() failed');
            //@codeCoverageIgnoreEnd
        }

        //do something with $curl ...
    }
}
```

This gets us the code coverage but the code isn't really tested.

The `FunctionRegistry` class alows you to _mock_ an internal PHP function

```php
class MyClassTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        // prepare the curl functions for mocking
        \Chadicus\FunctionRegistry::reset(__NAMESPACE__, array('curl'));
    }

    /**
     * @expectedExceptionMessage curl_init() failed
     */
    public function testCurlInitFails()
    {
        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_init',
            function () {
                return false;
            }
        );

        $myClass = new MyClass();

        // this will call our custom curl_init function
        $myClass->doSomething();
    }
}
```

For functions and constants, PHP will fall back to global functions or constants if a namespaced function or constant does not exist. It is because of this behavior that we can _mock_ internal functions.
