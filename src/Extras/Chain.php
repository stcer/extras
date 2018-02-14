<?php
namespace Dsheiko\Extras;

use Dsheiko\Extras\Arrays;
use Dsheiko\Extras\Collections;
use Dsheiko\Extras\Strings;
use Dsheiko\Extras\Functions;
use Dsheiko\Extras\Lib\TraitNormalizeClosure;

class Chain
{
    use TraitNormalizeClosure;

    private $value;

    public function __construct($value = [])
    {
        $this->value = $value;
    }
    /**
     * Factory method
     *
     * @param mixed $value
     * @return \Dsheiko\Extras\Chain
     */
    public static function from($value = [])
    {
        return new self($value);
    }

    /**
     * Return Extras class sutable for the last state of chain value
     * @param mixed $value
     * @return string
     * @throws \InvalidArgumentException
     */
    private static function guessExtrasClass(&$value): string
    {
        switch (true) {
            case is_array($value):
                return Arrays::class;
            case is_string($value):
                return Strings::class;
            case $value instanceof \ArrayObject:
            case $value instanceof \ArrayIterator:
            case $value instanceof \Traversable:
                return Collections::class;
            case is_callable($value):
                return Functions::class;
            case is_object($value):
                $ao = new \ArrayObject($value);
                $value = $ao->getArrayCopy();
                return Arrays::class;
        }
        throw new \InvalidArgumentException("Cannot find passing collection for given type");
    }

    /**
     * Handle request for non-defined method
     *
     * @param string $name
     * @param array $args
     * @return \Dsheiko\Extras\Chain
     */
    public function __call(string $name, array $args): Chain
    {
        $class = static::guessExtrasClass($this->value);
        $call = $class . "::" . $name;
        $this->value = \call_user_func_array($call, \array_merge([$this->value], $args));
        return $this;
    }

    /**
     * Bind a middleware function (function transforms the value)
     *
     * @param callable|string|Closure $callable $function
     * @return \Dsheiko\Extras\Chain
     */
    public function middleware($callable): Chain
    {
        $function = static::getClosure($callable);
        $this->value = $function($this->value);
        return $this;
    }

    /**
     * Get chain results (or an element by index)
     *
     * @param int $inx
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }
}