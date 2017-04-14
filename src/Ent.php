<?php
declare(strict_types=1);

namespace MXJ\Restructure;

/**
 * Structural object presenter
 *
 * @package MXJ\Restructure
 *
 * ```
 *  $structure = new Ent($data);
 *  foreach ($structure as $node) { ... }
 *
 *  $restructured = $structure->remap(['.path.to.key|value' => []]);
 *  foreach ($restructured as $node) { ... }
 * ```
 */
class Ent extends Iterator
{
    /**
     * Ent constructor.
     *
     * @param array $structure
     */
    public function __construct(array $structure)
    {
        parent::__construct($structure);
    }

    /**
     * Filter elements by pass it through callback
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function filter(callable $callback)
    {
        $this->resetFilters();
        $this->addFilter($callback);
        return $this;
    }

    /**
     * Find element('s) by it's attributes
     *
     * @param array ...$attributes
     *
     * @return \Generator
     */
    public function find(...$attributes)
    {

    }

    /**
     * Flatten tre structure to ...
     *
     * @param array $map
     *
     * @return array
     */
    public function flatten(array $map): array
    {
        $result = [];
        foreach ($this as $item) {
            $result[] = self::unfold($item);
        }

        return [];
    }

    private static function unfold($obj)
    {
        $properties = [];
        $properties = static::toArray($obj);
        return $properties;
    }
}
