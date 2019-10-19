<?php

use App\Checklist;
use App\Item;
use App\Template;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ItemTest extends TestCase
{

    /**
     * Complete item(s) 
     */
    public function testItemComplete(){
        $data['data'] = Item::get()->random(rand(1,5))->pluck('id')->map(function($item){
            return ['item_id' => $item];
        })->toArray();

        $this->post('/checklists/complete',$data,[]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            "data"=> [
               "*"=>[
                   "id",
                   "item_id",
                   "is_completed",
                   "checklist_id"
               ]
            ]
        ]);
    }

    /**
     * Incomplete item(s)
     */
    public function testItemIncomplete(){
        $data['data'] = Item::get()->random(rand(1,5))->pluck('id')->map(function($item){
            return ['item_id' => $item];
        })->toArray();

        $this->post('/checklists/incomplete',$data,[]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            "data"=> [
               "*"=>[
                   "id",
                   "item_id",
                   "is_completed",
                   "checklist_id"
               ]
            ]
        ]);
    }

    /**
     * Get all items by given {checklistId}
     */
    public function testItemChecklistGetItem()
    {
        $id = Checklist::pluck('id')->random();
        $this->get('/checklists'.'/'.$id.'/items',[]);

        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data'  =>[
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
                        "items" =>[
                            "*" =>[
                                "id",
                                "name",
                                "user_id",
                                "is_completed",
                                "due",
                                "urgency",
                                "checklist_id",
                                "assignee_id",
                                "task_id",
                                "completed_at",
                                "last_update_by",
                                "update_at",
                                "created_at"
                            ]
                        ]
                    ],
                    "links"=>[
                        "self"
                    ]
            ]
        ]);
    }

    /**
     * Create item by given checklistId
     */
    public function testItemChecklistStoreItem(){
        $data = factory(Item::class)->make(['due'=>function(){
            $date = new DateTime();
            return $date->format('Y-m-d H:i:s');
        }])->only(
            'description','due','urgency','assignee_id'
        );

        $id = Checklist::pluck('id')->random();
        $this->post('/checklists'.'/'.$id.'/items',$data,[]);
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            'data'  =>[
                    "type",
                    "id",
                    "attributes"=> [
                        "description",
                        "is_completed",
                        "completed_at",
                        "due",
                        "urgency",
                        "updated_by",
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
     * Get checklist item by given {checklistId} and {itemId}
     */
    public function testGetChecklistItem(){
        $checklist = Checklist::with('template.items')->get()->random();
        $checklistId= $checklist->id;
        $itemId =$checklist->template->items[0]['id'];
        $this->get('checklists/'.$checklistId.'/items'.'/'.$itemId,[]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data'  =>[
                "type",
                "id",
                "attributes"=> [
                    "description",
                    "is_completed",
                    "completed_at",
                    "due",
                    "urgency",
                    "updated_by",
                    "created_by",
                    "checklist_id",
                    "assignee_id",
                    "task_id",
                    "deleted_at",
                    "update_at",
                    "created_at",
                ],
                "links"=>[
                    "self"
                ]
        ]
        ]);
    }

    public function testUpdateChecklistItem(){
        $data = factory(Item::class)->make(['due'=>function(){
            $date = new DateTime();
            return $date->format('Y-m-d H:i:s');
        }])->only(
            'description','due','urgency','assignee_id'
        );

        $checklist = Checklist::with('template.items')->get()->random();
        $checklistId= $checklist->id;
        $itemId =$checklist->template->items[0]['id'];
        $this->patch('checklists/'.$checklistId.'/items'.'/'.$itemId,$data,[]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data'  =>[
                "type",
                "id",
                "attributes"=> [
                    "description",
                    "is_completed",
                    "completed_at",
                    "due",
                    "urgency",
                    "updated_by",
                    "created_by",
                    "checklist_id",
                    "assignee_id",
                    "task_id",
                    "deleted_at",
                    "update_at",
                    "created_at",
                ],
                "links"=>[
                    "self"
                ]
        ]
        ]);
    }

    public function testdestroyChecklistItem(){
        $checklist = Checklist::with('template.items')->get()->random();
        $checklistId= $checklist->id;
        $itemId = $checklist->template->items->random()->id;
        $this->delete('checklists/'.$checklistId.'/items'.'/'.$itemId,[],[]);
        $this->seeStatusCode(204);

    }

    public function testItemsList(){
        $this->get('checklists/items',[]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data'  =>[
                '*'  =>[
                    "type",
                    "id",
                    "attributes"=> [
                        "description",
                        "is_completed",
                        "completed_at",
                        "due",
                        "urgency",
                        "updated_by",
                        "created_by",
                        "checklist_id",
                        "assignee_id",
                        "task_id",
                        "deleted_at",
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



}
