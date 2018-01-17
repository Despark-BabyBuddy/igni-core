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
     * moves $this model after $entity model (and rearrange all entities).
     *
     * @param Model $entity
     *
     * @throws \Exception
     */
    public function moveAfter($entity, $field)
    {
        $this->move('moveAfter', $entity, $field);
    }

    /**
     * moves $this model before $entity model (and rearrange all entities).
     *
     * @param Model $entity
     *
     * @throws SortableException
     */
    public function moveBefore($entity, $field)
    {
        $this->move('moveBefore', $entity, $field);
    }

    /**
     * @param string $action moveAfter/moveBefore
     * @param Model  $entity
     *
     * @throws SortableException
     */
    public function move($action, $entity, $sortableField)
    {
        $this->checkSortableGroupField(static::getSortableGroupField(), $entity);
        $this->_transaction(function () use ($entity, $action, $sortableField) {
            $oldPosition = $this->getAttribute($sortableField);
            $newPosition = $entity->getAttribute($sortableField);

            if ($oldPosition === $newPosition) {
                return;
            }

            $isMoveBefore = $action === 'moveBefore'; // otherwise moveAfter
            $isMoveForward = $oldPosition < $newPosition;
            if ($isMoveForward) {
                $this->queryBetween($oldPosition, $newPosition, $sortableField)->decrement($sortableField);
            } else {
                $this->queryBetween($newPosition, $oldPosition, $sortableField)->increment($sortableField);
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
    protected function queryBetween($left, $right, $sortableField)
    {
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
