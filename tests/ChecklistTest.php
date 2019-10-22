<?php

use App\Checklist;
use App\Item;
use App\Template;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ChecklistTest extends TestCase
{


    /**
     * Get checklist by given checklistId. 
     */
    public function testChecklistShow(){
        $id = Checklist::get()->random()->id;
        $this->get('/checklists/'.$id,$this->header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            "data"=> [
                "type",
                "id",
                "attributes"=> [
                    "object_domain",
                    "object_id",
                    "description",
                    "is_completed",
                    "due",
                    "urgency",
                    "completed_at",
                    "last_update_by",
                    "updated_at",
                    "created_at",
                ],
                "links"=>[
                    "self"
                ]
            ]
        ]);
    }

    /**
     * Get list of checklists.
     */
    public function testChecklistList()
    {
        $this->get('/checklists',$this->header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data'  =>[
                '*'=>[
                    "type",
                    "id",
                    "attributes"=> [
                        "object_domain",
                        "object_id",
                        "description",
                        "is_completed",
                        "due",
                        "task_id",
                        "urgency",
                        "completed_at",
                        "last_update_by",
                        "updated_at",
                        "created_at",
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

    public function testChecklistStore(){
        $data = factory(Checklist::class)->make([
            'object_id'=>function() {
                return Template::get()->random()->id;
            },
            'task_id'=>function(){
                return Template::get()->random()->id;
            },
            'items' => function() {
                for ($i=0; $i < rand(1,5); $i++) { 
                    $items[] = uniqid("tes - $i - ");
                }
                return $items;
            }
        ])->only(
            'object_domain','object_id','due','urgency','description','items','task_id'
        );
        $date = new DateTime();
        $due = $date->format('Y-m-d H:i:s');
        $data['due']=$due;

        $this->post('/checklists',$data,$this->header());
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            "data"=> [
                "type",
                "id",
                "attributes"=> [
                    "object_domain",
                    "object_id",
                    "task_id",
                    "description",
                    "is_completed",
                    "due",
                    "urgency",
                    "completed_at",
                    "updated_by",
                    "created_by",
                    "updated_at",
                    "created_at",
                ],
                "links"=>[
                    "self"
                ]
            ]
        ]);
    }


    public function testChecklistUpdate(){
        $checklist = Checklist::get()->random();

        $data = factory(Checklist::class)->make([
            'created_at'=>function() use($checklist){
                return $checklist->created_at;
            },
            'object_id'=>function() use($checklist){
                return $checklist->object_id;
            }
        ])->only(
            'object_domain','object_id','is_completed','description'
        );

        $this->patch('/checklists'.'/'.$checklist->id,$data,$this->header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            "data"=> [
                "type",
                "id",
                "attributes"=> [
                    "object_domain",
                    "object_id",
                    "description",
                    "is_completed",
                    "due",
                    "urgency",
                    "completed_at",
                    "last_update_by",
                    "updated_at",
                    "created_at",
                ],
                "links"=>[
                    "self"
                ]
            ]
        ]);
    }

    public function testChecklistDestroy(){
        // $id = 94;
        $id = Checklist::get()->random()->id;
        $this->delete('/checklists'.'/'.$id,[],$this->header());
        $this->seeStatusCode(204);

    }



}
