<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProductType extends ObjectType
{
    public function __construct()
    {
        $config=[
            'description'=>'Product object',
            'fields'=>function (){
                return[
                  'id'=>[
                      'type'=> Types::int(),
                      'description'=> 'Product identifier',
                  ],
                  'name'=>[
                      'type'=> Types::string(),
                      'description'=> 'Product name',
                  ],
                  'price'=>[
                      'type'=> Types::int(),
                      'description'=> 'Product price',
                  ],
                    'components'=>[
                        'type'=>Types::listOf(Types::product()),
                        'description'=>'components of 1 product',
                        'resolve'=>function ($root){
                            return DB::select("
                                
                                SELECT p.* FROM products_components_table AS c
                                    LEFT JOIN products_table AS p
                                        ON c.componentId = p.id
                                        WHERE c.productId={$root->id}
                                
                            ");



                        }
                    ]
                ];
            },
        ];

        parent::__construct($config);
    }
}

