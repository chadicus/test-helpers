<?php
namespace Chadicus;

/**
 * This class if for overriding global functions for use within unit tests.
 */
final class FunctionRegistry
{
    /**
     * Array of custom functions.
     *
     * @var callable[]
     */
    private static $functions = array();

    /**
     * Register a new function for testing.
     *
     * @param string   $name     The function name.
     * @param callable $function The callable to execute.
     *
     * @return void
     */
    public static function set($name, callable $function)
    {
        self::$functions[$name] = $function;
    }

    /**
     * Returns the custom function or the global function.
     *
     * @param string $name The function name.
     *
     * @return callable
     */
    public static function get($name)
    {
        if (\array_key_exists($name, self::$functions)) {
            return self::$functions[$name];
        }

        // return reference to global function
        return "\\{$name}";
    }

    /**
     * Sets all custom function properties to null.
     *
     * @param string $namespace  The namespace from which the global function will be called.
     * @param array  $extensions Array of PHP extensions whose functions will be mocked.
     *
     * @return void
     */
    public static function reset($namespace, array $extensions = array())
    {
        self::$functions = array();
        foreach ($extensions as $extension) {
            foreach (\get_extension_funcs($extension) as $name) {
                //If it's already defined skip it.
                if (\function_exists("{$namespace}\\{$name}")) {
                    continue;
                }

                eval("
                    namespace {$namespace};
                    function {$name}() {
                        return \call_user_func_array(\Chadicus\FunctionRegistry::get('{$name}'), \\func_get_args());
                    }
                ");
            }
        }
    }
}
