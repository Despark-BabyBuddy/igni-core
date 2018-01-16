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
     * Check if the class has sortable property which is not empty.
     *
     * @return boolean
     */
    public function isSortable(): bool
    {
        return $this->sortableField && !empty($this->sortableField) ? true : false;
    }
}
