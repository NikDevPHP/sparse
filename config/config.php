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

return [

    /*
    |--------------------------------------------------------------------------
    | Sparse Database Tables
    |--------------------------------------------------------------------------
    */

    'tables' => [

        /*
        |--------------------------------------------------------------------------
        | Attributes Table
        |--------------------------------------------------------------------------
        |
        | Specify database table name that should be used to store
        | your attributes. You may use whatever you like.
        |
        | Default: "attributes"
        |
        */

        'attributes' => 'attributes',

        /*
        |--------------------------------------------------------------------------
        | Entity Attributes Table
        |--------------------------------------------------------------------------
        |
        | Specify database table name that should be used to store the relation
        | between "entities" and "attributes". You may use whatever you like.
        |
        | Default: "attribute_entity"
        |
        */

        'attribute_entity' => 'attribute_entity',

        /*
        |--------------------------------------------------------------------------
        | Boolean Values Table
        |--------------------------------------------------------------------------
        |
        | Specify database table name that should be used to store
        | your boolean values. You may use whatever you like.
        |
        | Default: "values_boolean"
        |
        */

        'values_boolean' => 'values_boolean',

        /*
        |--------------------------------------------------------------------------
        | Datetime Values Table
        |--------------------------------------------------------------------------
        |
        | Specify database table name that should be used to store
        | your datetime values. You may use whatever you like.
        |
        | Default: "values_datetime"
        |
        */

        'values_datetime' => 'values_datetime',

        /*
        |--------------------------------------------------------------------------
        | Integer Values Table
        |--------------------------------------------------------------------------
        |
        | Specify database table name that should be used to store
        | your integer values. You may use whatever you like.
        |
        | Default: "values_integer"
        |
        */

        'values_integer' => 'values_integer',

        /*
        |--------------------------------------------------------------------------
        | Varchar Values Table
        |--------------------------------------------------------------------------
        |
        | Specify database table name that should be used to store
        | your varchar values. You may use whatever you like.
        |
        | Default: "values_varchar"
        |
        */

        'values_varchar' => 'values_varchar',

    ],

];
