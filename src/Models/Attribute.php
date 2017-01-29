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

namespace Rinvex\Sparse\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Facades\DB;
use Spatie\EloquentSortable\Sortable;
use Rinvex\Cacheable\CacheableEloquent;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\EloquentSortable\SortableTrait;

class Attribute extends Model implements Sortable
{
    use HasSlug;
    use SortableTrait;
    use HasTranslations;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'order',
        'group',
        'type',
        'entities',
        'collection',
        'default',
    ];

    /**
     * {@inheritdoc}
     */
    public $translatable = [
        'name',
        'description',
    ];

    /**
     * {@inheritdoc}
     */
    public $sortable = ['order_column_name' => 'order'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('rinvex.sparse.tables.attributes'));
    }

    /**
     * Get the entities attached to this attribute.
     *
     * @return \Illuminate\Support\Collection|null
     */
    public function entities()
    {
        return DB::table('sparse_attribute_entity')->where('attribute_id', $this->getKey())->get()->pluck('entity_type');
    }

    /**
     * Check if attribute is multivalued.
     *
     * @return bool
     */
    public function isCollection()
    {
        return (bool) $this->getAttribute('collection');
    }

    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->generateSlugsFrom('name')
                          ->saveSlugsTo('slug');
    }

    /**
     * Set the attribute attached entities.
     *
     * @param \Illuminate\Support\Collection|array $value
     *
     * @return void
     */
    public function setEntitiesAttribute($entities)
    {
        static::saved(function ($model) use ($entities) {
            $values = [];
            foreach ($entities as $entity) {
                $values[] = ['attribute_id' => $model->id, 'entity_type' => $entity];
            }

            DB::table('sparse_attribute_entity')->where('attribute_id', $model->id)->delete();
            DB::table('sparse_attribute_entity')->insert($values);
        });
    }
}
