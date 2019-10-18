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
        $this->get('/checklists/'.$id,[]);
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
                    "update_at",
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
        $this->get('/checklists',[]);
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
                        "urgency",
                        "completed_at",
                        "last_update_by",
                        "update_at",
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
        // $data= $this->dataTemplate();
        // $this->post('/checklists',$data,[]);
        // $this->seeStatusCode(201);
        // $this->addTemplateJson();
    }


    public function testChecklistUpdate(){
        // $id = Template::get()->random()->id;
        // $data = $this->dataTemplate();
        // $this->patch('/checklists/'.$id,$data,[]);
        // $this->seeStatusCode(200);
        // $this->addTemplateJson();
    }

    public function testChecklistDestroy(){
        // $id = 51;
        // $id = Template::get()->random()->id;
        // $this->delete('/checklists/'.$id,[],[]);
        // $this->seeStatusCode(204);

    }



}
