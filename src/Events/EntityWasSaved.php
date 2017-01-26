<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Rinvex Sparse Package.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Rinvex Sparse Package
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

declare(strict_types=1);

namespace Rinvex\Sparse\Events;

use Exception;
use Rinvex\Sparse\Models\Value;
use Rinvex\Sparse\Support\ValueCollection;
use Illuminate\Database\Eloquent\Model as Entity;

class EntityWasSaved
{
    /**
     * The entity instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $entity;

    /**
     * The trash collection.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $trash;

    /**
     * Save values when an entity is saved.
     *
     * @param \Illuminate\Database\Eloquent\Model $entity
     *
     * @throws \Exception
     *
     * @return void
     */
    public function handle(Entity $entity)
    {
        $this->entity = $entity;
        $this->trash = $this->entity->getEntityAttributeValueTrash();

        // Wrap the whole process inside database transaction
        $connection = $this->entity->getConnection();
        $connection->beginTransaction();

        try {
            foreach ($this->entity->getEntityAttributes() as $attribute) {
                if ($this->entity->relationLoaded($relation = $attribute->getAttribute('slug'))) {
                    $relationValue = $this->entity->getRelationValue($relation);

                    if ($relationValue instanceof ValueCollection) {
                        foreach ($relationValue as $value) {
                            $this->saveOrTrashValue($value->setAttribute('entity_id', $this->entity->getKey()));
                        }
                    } elseif (! is_null($relationValue)) {
                        $this->saveOrTrashValue($relationValue->setAttribute('entity_id', $this->entity->getKey()));
                    }
                }
            }

            if ($this->trash->count()) {
                // Fetch the first item's class to know the model used for deletion
                $class = get_class($this->trash->first());

                // Let's batch delete all the values based on their ids
                $class::whereIn('id', $this->trash->pluck('id'))->delete();

                // Now, empty the trash
                $this->trash = collect([]);
            }
        } catch (Exception $e) {
            // Rollback transaction on failure
            $connection->rollBack();

            throw $e;
        }

        // Commit transaction on success
        $connection->commit();
    }

    /**
     * Save or trash the given value according to it's content.
     *
     * @param \Rinvex\Sparse\Models\Value $value
     *
     * @return void
     */
    protected function saveOrTrashValue(Value $value)
    {
        // In order to provide flexibility and let the values have their own
        // relationships, here we'll check if a value should be completely
        // saved with its relations or just save its own current state.
        if (! is_null($value) && ! $this->trashValue($value)) {
            if ($value->shouldPush()) {
                $value->push();
            } else {
                $value->save();
            }
        }
    }

    /**
     * Trash the given value.
     *
     * @param \Rinvex\Sparse\Models\Value $value
     *
     * @return bool
     */
    protected function trashValue(Value $value)
    {
        if (! is_null($value->getAttribute('content'))) {
            return false;
        }

        if ($value->exists) {
            // Push value to the trash
            $this->trash->push($value);
        }

        return true;
    }
}
