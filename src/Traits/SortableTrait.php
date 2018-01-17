<?php

namespace Despark\Cms\Traits;

use Rutorika\Sortable\SortableTrait as Sortable;

/**
 * Sortable trait
 * extends Rutorika\Sortable\SortableTrait
 */
trait SortableTrait
{
    use Sortable;

    /**
     * Get sortable fields
     * 
     * @return array
     */
    public function getSortableField(): array
    {
        return isset($this->sortableFields) ? $this->sortableFields : [];
    }

    /**
     * Get a new query builder for the model's table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    abstract public function newQuery($excludeDeleted = true);
}
