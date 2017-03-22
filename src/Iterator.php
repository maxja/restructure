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
        $this->__structure = self::traverse($this->__collection);
    }

    /**
     * Traverse trough elements
     *
     * @param array $collection
     * @param int   $level
     *
     * @return \Generator
     */
    public static function &traverse(array &$collection, int $level = 0)
    {
        foreach ($collection as $key => &$value) {
            yield $key => [&$value, $level];

            if ((!~self::$max_depth || $level < self::$max_depth) && is_array($value)) {
                foreach (self::traverse($value, $level + 1) as $k => &$v) {
                    yield $k => $v;
                }
            }
            unset($v, $k);
        }
        unset($value, $key);
    }
}