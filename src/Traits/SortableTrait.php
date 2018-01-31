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
     * Adds position to model on creating event.
     */
    public static function bootSortableTrait()
    {
        static::creating(
            function ($model) {
                /* @var Model $model */
                $sortableFields = $model->getSortableFieldsKeys();
                $query = static::applySortableGroup(static::on($model->getConnectionName()), $model);

                foreach ($sortableFields as $sortableField) {
                    // only automatically calculate next position with max+1 when a position has not been set already
                    if ($model->$sortableField === null || $model->$sortableField === 1) {
                        $model->setAttribute($sortableField, $query->max($sortableField) + 1);
                    }
                }
            }
        );
    }

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
     * Get the keys from the sortable fields
     *
     * @return array
     */
    public function getSortableFieldsKeys(): array
    {
        return array_keys($this->getSortableFields());
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
}
