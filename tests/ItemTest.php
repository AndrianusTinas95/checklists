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
        $items=Item::where('is_completed',true)->get();
        $data['data'] = $items->random(rand(0,$items->count()))->pluck('id')->map(function($item){
            return ['item_id' => $item];
        })->toArray();

        $this->post('/checklists/complete',$data,$this->header());
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
        $items=Item::where('is_completed',false)->get();
        $data['data'] = $items->random(rand(0,$items->count()))->pluck('id')->map(function($item){
            return ['item_id' => $item];
        })->toArray();

        $this->post('/checklists/incomplete',$data,$this->header());
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
        $this->get('/checklists'.'/'.$id.'/items',$this->header());

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
                        "updated_at",
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
                                "updated_at",
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
        $data = factory(Item::class)->make([
            'assignee_id'=>function(){
                return Template::get()->random()->id;
            }
        ])->only(
            'description','urgency','assignee_id'
        );
        $date = new DateTime();
        $due = $date->format('Y-m-d H:i:s');
        $data['due']=$due;

        $id = Checklist::pluck('id')->random();
        $this->post('/checklists'.'/'.$id.'/items',$data,$this->header());
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
     * Get checklist item by given {checklistId} and {itemId}
     */
    public function testGetChecklistItem(){
        $checklist = Checklist::has('template.items')->get()->random();
        $checklistId= $checklist->id;
        $itemId =$checklist->template->items[0]['id'];
        $this->get('checklists/'.$checklistId.'/items'.'/'.$itemId,$this->header());
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
                    "updated_at",
                    "created_at",
                ],
                "links"=>[
                    "self"
                ]
        ]
        ]);
    }

    public function testUpdateChecklistItem(){
        $checklist = Checklist::has('template.items')->get()->random();
        $checklistId= $checklist->id;
        $template =$checklist->template;
        $itemId =$checklist->template->items[0]['id'];

        $data = factory(Item::class)->make([
            'assignee_id'=>function() use($template){
                return $template->id;
            }
        ])->only(
            'description','due','urgency','assignee_id'
        );
        $date = new DateTime();
        $due = $date->format('Y-m-d H:i:s');
        $data['due']=$due;
        
        $this->patch('checklists/'.$checklistId.'/items'.'/'.$itemId,$data,$this->header());
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
                    "updated_at",
                    "created_at",
                ],
                "links"=>[
                    "self"
                ]
        ]
        ]);
    }

    public function testdestroyChecklistItem(){
        $checklist = Checklist::has('template.items')->get()->random();
        $checklistId= $checklist->id;
        $itemId = $checklist->template->items->random()->id;
        $this->delete('checklists/'.$checklistId.'/items'.'/'.$itemId,[],$this->header());
        $this->seeStatusCode(204);

    }

    public function testItemsList(){
        $this->get('checklists/items',$this->header());
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



}
