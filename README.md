# Chadicus Test Helpers
[![Build Status](http://img.shields.io/travis/chadicus/test-helpers.svg?style=flat)](https://travis-ci.org/chadicus/test-helpers)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/chadicus/test-helpers.svg?style=flat)](https://scrutinizer-ci.com/g/chadicus/test-helpers/)
[![Code Coverage](http://img.shields.io/coveralls/chadicus/test-helpers.svg?style=flat)](https://coveralls.io/r/chadicus/test-helpers)
[![Latest Stable Version](http://img.shields.io/packagist/v/chadicus/test-helpers.svg?style=flat)](https://packagist.org/packages/chadicus/test-helpers)
[![Total Downloads](http://img.shields.io/packagist/dt/chadicus/test-helpers.svg?style=flat)](https://packagist.org/packages/chadicus/test-helpers)
[![License](http://img.shields.io/packagist/l/chadicus/test-helpers.svg?style=flat)](https://packagist.org/packages/chadicus/test-helpers)


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

## Requirements

Test Helpers requires PHP 5.4 (or later).

##Composer
To add the library as a local, per-project dependency use [Composer](http://getcomposer.org)! Simply add a dependency on
`chadicus/test-helpers` to your project's `composer.json` file such as:

```json
{
    "require-dev": {
        "chadicus/test-helpers": "~1.0"
    }
}
```

NOTE: test-helpers should never be used in production. They are meant for testing enviornments only.

##Documentation
PHP docs for the project can be found [here](http://chadicus.github.io/test-helpers).

##Contact
Developers may be contacted at:

 * [Pull Requests](https://github.com/chadicus/test-helpers/pulls)
 * [Issues](https://github.com/chadicus/test-helpers/issues)

##Project Build
With a checkout of the code get [Composer](http://getcomposer.org) in your PATH and run:

```sh
./build.php
```
