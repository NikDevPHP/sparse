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

namespace Rinvex\Sparse\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Entity;

class EagerLoadScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $entity
     *
     * @return void
     */
    public function apply(Builder $builder, Entity $entity)
    {
        $eagerLoads = $builder->getEagerLoads();

        // If there is any eagerload matching the eav key, we will replace it with
        // all the registered properties for the entity. We'll simulate as if the
        // user has manually added all of these withs in purpose when querying.
        if (array_key_exists('eav', $eagerLoads)) {
            $eagerLoads = array_merge($eagerLoads, $entity->getEntityAttributeRelations());

            $builder->setEagerLoads(array_except($eagerLoads, 'eav'));
        }
    }
}
