<?php
declare(strict_types = 1);

namespace MXJ\Restructure;

/**
 * Iterator class
 *
 * @package MXJ\Restructure
 */
class Iterator implements \Iterator
{
    /**
     * @constant int
     */
    const MAX_DEPTH = -1;

    /**
     * @var int
     */
    private static $max_depth = self::MAX_DEPTH;

    /**
     * @var array
     */
    private $__collection;

    /**
     * @var array
     */
    private $__filters = [];

    /**
     * @var \Generator
     */
    private $__structure;

    /**
     * Iterator constructor.
     *
     * @param $structure
     */
    public function __construct(array $structure)
    {
        $this->__collection = $structure;
    }

    /**
     * Set current depth
     *
     * @param int $depth
     */
    public function setMaxDepth(int $depth = self::MAX_DEPTH)
    {
        self::$max_depth = $depth;
    }

    /**
     * Current max depth
     *
     * @return int
     */
    public function getMaxDepth(): int
    {
        return self::$max_depth;
    }

    /**
     * Return the current element
     *
     * @inheritdoc
     */
    public function current()
    {
        return $this->__structure->current();
    }

    /**
     * Move forward to next element
     *
     * @inheritdoc
     */
    public function next()
    {
        $this->__structure->next();
    }

    /**
     * Return the key of the current element
     *
     * @inheritdoc
     */
    public function key()
    {
        return $this->__structure->key();
    }

    /**
     * Checks if current position is valid
     *
     * @inheritdoc
     */
    public function valid(): bool
    {
        return $this->__structure->valid();
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @inheritdoc
     */
    public function rewind()
    {
        $this->__structure = self::traverse($this->__collection, 0, $this->__filters);
    }

    /**
     * Add new filter to filter stack
     *
     * @param callable $filter
     */
    protected function addFilter(callable $filter)
    {
        $this->__filters[] = $filter;
    }

    /**
     * Reset filters list
     */
    protected function resetFilters()
    {
        unset($this->__filters);
    }

    /**
     * Traverse trough elements
     *
     * @param array $collection
     * @param int   $level
     * @param array $filters
     *
     * @return \Generator
     */
    private static function &traverse(array &$collection, int $level = 0, array $filters)
    {
        foreach ($collection as $key => &$value) {
            if ($filters !== []
                && self::some($filters, function ($filter, $k, $v) {
                    return !$filter($k, $v);
                }, $key, $value)
            ) {
                continue;
            }

            yield $key => $value;

            if ((!~self::$max_depth || $level < self::$max_depth) && is_array($value)) {
                foreach (self::traverse($value, $level + 1, $filters) as $k => $v) {
                    yield $k => $v;
                }
            }
            unset($v, $k);
        }
        unset($value, $key);
    }

    /**
     * Rejection mechanism to get true if any one element qualified
     *
     * @param array         $samples
     * @param callable|null $callback
     * @param array         ...$arguments
     *
     * @return bool
     */
    private static function some(array $samples, callable $callback = null, ...$arguments): bool
    {
        if ($callback === null) {
            $callback = function ($element, ...$arguments): bool {
                if ($element instanceof \Closure) {
                    return $element(...$arguments);
                }

                return !!$element;
            };
        }

        foreach ($samples as $sample) {
            if ($callback($sample, ...$arguments)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Rejection mechanism to get true if only every element qualified
     *
     * @param array         $samples
     * @param callable|null $callback
     * @param array         ...$arguments
     *
     * @return bool
     */
    private static function every(array $samples, callable $callback = null, ...$arguments): bool
    {
        if ($callback === null) {
            $callback = function ($element, ...$arguments): bool {
                if ($element instanceof \Closure) {
                    return $element(...$arguments);
                }

                return !!$element;
            };
        }

        foreach ($samples as $sample) {
            if (!$callback($sample, ...$arguments)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Return generator as array
     *
     * @return array
     */
    public function toArray(): array
    {
        return iterator_to_array($this);
    }
}