<?php

use App\History;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class HistoryTest extends TestCase
{

    /**
     * Get history by given historyId. 
     */
    public function testHistoriesShow(){
        $id = History::get()->random()->id;
        $this->get('/checklists/histories/'.$id,$this->header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            "data"=> [
                "type",
                "id",
                "attributes"=> [
                    'loggable_type',
                    'loggable_id',
                    'action',
                    'kwuid',
                    'value',
                    'created_at',
                    'updated_at'
                ],
                "links"=>[
                    "self"
                ]
            ]
        ]);
    }

    /**
     * Get list of history.
     */
    public function testHistoriesList()
    {
        $this->get('/checklists/histories',$this->header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data'  =>[
                '*'=>[
                    "type",
                    "id",
                    "attributes"=> [
                        'loggable_type',
                        'loggable_id',
                        'action',
                        'kwuid',
                        'value',
                        'created_at',
                        'updated_at'
                    ],
                    "links"=>[
                        "self"
                    ]
                ]
            ],
            'meta' => [
                'count',
                'total'
            ],
            'links' => [
                'first',
                'last',
                'next',
                'prev'
            ]
        ]);
    }




}
