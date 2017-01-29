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

class CreateValuesBooleanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('rinvex.sparse.tables.values_boolean'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->boolean('content');
            $table->unsignedInteger('attribute_id');
            $table->unsignedInteger('entity_id');

            // Indexes
            $table->foreign('attribute_id')
                  ->references('id')
                  ->on('sparse_attributes')
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
        Schema::drop(config('rinvex.sparse.tables.values_boolean'));
    }
}
