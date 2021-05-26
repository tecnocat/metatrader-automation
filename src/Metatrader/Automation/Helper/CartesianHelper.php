<?php

namespace App\Metatrader\Automation\Helper;

/**
 * Credits to bpolaszek/cartesian-product
 */
class CartesianHelper implements \Countable, \IteratorAggregate
{
    private array $set;
    private bool  $recursive;
    private int   $count;

    public function __construct(array $set, bool $recursive = false)
    {
        $this->set       = $set;
        $this->recursive = $recursive;
        $this->count     = -1;
    }

    public function asArray(): array
    {
        return iterator_to_array($this);
    }

    public function count(): int
    {
        if (-1 === $this->count)
        {
            $this->count = array_product(
                array_map(
                    function ($subset, $key)
                    {
                        $this->validate($subset, $key);

                        return count($subset);
                    },
                    $this->set,
                    array_keys($this->set)
                )
            );
        }

        return $this->count;
    }

    public function getIterator(): \Generator
    {
        if ([] === $this->set)
        {
            if (true === $this->recursive)
            {
                yield [];
            }

            return;
        }

        $set    = $this->set;
        $keys   = array_keys($set);
        $key    = end($keys);
        $subset = array_pop($set);
        $this->validate($subset, $key);

        foreach (self::subset($set) as $product)
        {
            foreach ($subset as $value)
            {
                yield $product + [$key => ($value instanceof \Closure ? $value($product) : $value)];
            }
        }
    }

    private static function subset(array $set): CartesianHelper
    {
        return new self($set, true);
    }

    private function validate($subset, $key): void
    {
        if (is_array($subset) && !empty($subset))
        {
            return;
        }

        if ($subset instanceof \Traversable && $subset instanceof \Countable && count($subset) > 0)
        {
            return; // @codeCoverageIgnore
        }

        throw new \InvalidArgumentException(sprintf('Key "%s" should return a non-empty iterable', $key));
    }
}
