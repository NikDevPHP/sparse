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

declare(strict_types = 1);

namespace Rinvex\Sparse\Support;

use Closure;
use Rinvex\Sparse\Models\Attribute;
use Illuminate\Database\Eloquent\Model as Entity;

class RelationBuilder
{
    /**
     * Build the relations for the entity attributes.
     *
     * @param \Illuminate\Database\Eloquent\Model $entity
     *
     * @return void
     */
    public function build(Entity $entity)
    {
        $attributes = $entity->getEntityAttributes();

        // We will manually add a relationship for every attribute registered
        // of this entity. Once we know the relation method we have to use,
        // we will just add it to the entityAttributeRelations property.
        foreach ($attributes as $attribute) {
            $relation = $this->getRelationClosure($entity, $attribute);

            $entity->setEntityAttributeRelation($attribute->getAttribute('slug'), $relation);
        }
    }

    /**
     * Generate the entity attribute relation closure.
     *
     * @param \Illuminate\Database\Eloquent\Model $entity
     * @param \Rinvex\Sparse\Models\Attribute     $attribute
     *
     * @return \Closure
     */
    protected function getRelationClosure(Entity $entity, Attribute $attribute)
    {
        $method = $attribute->isCollection() ? 'hasMany' : 'hasOne';

        // This will return a closure fully binded to the current entity instance,
        // which will help us to simulate any relation as if it was made in the
        // original entity class definition using a function statement.
        return Closure::bind(function () use ($entity, $attribute, $method) {
            $relation = $entity->$method($attribute->getAttribute('type'), 'entity_id', 'id');

            // We add a where clause in order to fetch only the elements that are
            // related to the given attribute. If no condition is set, it will
            // fetch all the value rows related to the current entity.
            return $relation->where($attribute->getForeignKey(), $attribute->getKey());
        }, $entity, get_class($entity));
    }
}
