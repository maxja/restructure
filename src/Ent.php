<?php
declare(strict_types = 1);

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
     * @var array
     */
    protected $_anatomy;

    /**
     * Ent constructor.
     *
     * @param array $structure
     */
    public function __construct(array $structure)
    {
        parent::__construct($structure);
/*
        foreach ($this as $key => $value) {
            $this->_anatomy[] = array_merge([$key], $value);
        }*/
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
}
