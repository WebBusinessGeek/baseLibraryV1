<?php
/**
 * Created by PhpStorm.
 * User: MacBookEr
 * Date: 4/7/15
 * Time: 2:11 PM
 */

namespace Base;


class MockBaseModelForOneToOne extends BaseModel {

    protected $table = 'mockBaseModelForOneToOnes';
    protected $fillable = ['attribute1','attribute2','attribute3'];

    protected $modelAttributes = [
        //		START AT ZERO (0)!!! => [
        //
        //			'name' => 'nameOfAttribute',
        //
        //			'format' => '(choose 1: email, phoneNumber, url, password,
        //							 string, exists, enum, text, id, token, ipAddress, date)',
        //
        //			'nullable' => false,
        //
        //			'unique' => true,
        //
        //			'exists' => null,
        //
        //          'identifier' => false,
        //
        //          'key' => false,
        //
        //
        //			'enumValues' => [
        //				'item1',
        //				'item2',
        //				'item3'
        //			]
        //		],

        0 => [
            'name' => 'attribute1',

            'format' => 'string',

            'nullable' => false,

            'unique' => true,

            'exists' => null,

            'identifier' => false,

            'key' => false,

        ],

        1 => [

            'name' => 'attribute2',

            'format' => 'string',

            'nullable' => false,

            'unique' => false,

            'exists' => null,

            'identifier' => false,

            'key' => false,
        ],

        2 => [

            'name' => 'attribute3',

            'format' => 'string',

            'nullable' => true,

            'unique' => true,

            'exists' => null,

            'identifier' => false,

            'key' => false,
        ],

        3 => [

            'name' => 'attribute4',

            'format' => 'email',

            'nullable' => false,

            'unique' => true,

            'exists' => null,

            'identifier' => false,

            'key' => false,
        ]
    ];



}