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
    public function getSortableFields(): array
    {
        return isset($this->sortableFields) ? $this->sortableFields : [];
    }

    /**
     * @param string $action moveAfter/moveBefore
     * @param Model  $entity
     *
     * @throws SortableException
     */
    public function move($action, $entity)
    {
        $this->checkSortableGroupField(static::getSortableGroupField(), $entity);
        $this->_transaction(function () use ($entity, $action) {
            $sortableField = 'bump_sort_position';

            $oldPosition = $this->getAttribute($sortableField);
            $newPosition = $entity->getAttribute($sortableField);

            if ($oldPosition === $newPosition) {
                return;
            }

            $isMoveBefore = $action === 'moveBefore'; // otherwise moveAfter
            $isMoveForward = $oldPosition < $newPosition;
            if ($isMoveForward) {
                $this->queryBetween($oldPosition, $newPosition)->decrement($sortableField);
            } else {
                $this->queryBetween($newPosition, $oldPosition)->increment($sortableField);
            }

            $this->setAttribute($sortableField, $this->getNewPosition($isMoveBefore, $isMoveForward, $newPosition));
            $entity->setAttribute($sortableField, $this->getNewPosition(!$isMoveBefore, $isMoveForward, $newPosition));

            $this->save();
            $entity->save();
        });
    }

    /**
     * @param $left
     * @param $right
     *
     * @return QueryBuilder
     */
    protected function queryBetween($left, $right)
    {
        $sortableField = 'bump_sort_position';
        $query = static::applySortableGroup($this->newQuery(), $this);

        return $query->where($sortableField, '>', $left)->where($sortableField, '<', $right);
    }

    /**
     * Get a new query builder for the model's table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    abstract public function newQuery($excludeDeleted = true);
}
