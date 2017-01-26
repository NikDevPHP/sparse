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

namespace Rinvex\Sparse\Models\Data;

use Rinvex\Sparse\Models\Value;

class Boolean extends Value
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'sparse_values_boolean';

    /**
     * {@inheritdoc}
     */
    protected $casts = ['content' => 'boolean'];
}
