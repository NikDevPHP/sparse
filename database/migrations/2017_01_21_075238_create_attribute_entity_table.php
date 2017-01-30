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

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeEntityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('rinvex.sparse.tables.attribute_entity'), function (Blueprint $table) {
            // Columns
            $table->unsignedInteger('attribute_id');
            $table->string('entity_type');

            // Indexes
            $table->unique(['attribute_id', 'entity_type'], 'sparse_attribute_id_entity_type');
            $table->foreign('attribute_id')
                  ->references('id')
                  ->on(config('rinvex.sparse.tables.attributes'))
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            // Engine
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(config('rinvex.sparse.tables.attribute_entity'));
    }
}
