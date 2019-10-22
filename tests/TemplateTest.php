<?php

use App\Checklist;
use App\Item;
use App\Template;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TemplateTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTemplateList()
    {
        $this->get('/checklists/templates',$this->header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data'  =>[
                '*'=>[
                    'name',
                    'checklist'=>[
                        'description',
                        'due_interval',
                        'due_unit'
                    ],
                    'items'=>[
                        '*'=>[
                            'description',
                            'urgency',
                            'due_interval',
                            'due_unit'
                        ]
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

    public function testTemplateStore(){
        $data= $this->dataTemplate();
        $this->post('/checklists/templates',$data,$this->header());
        $this->seeStatusCode(201);
        $this->addTemplateJson();
    }

    public function testTemplateShow(){
        $id = Template::get()->random()->id;
        $this->get('/checklists/templates/'.$id,$this->header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            "data"=> [
                "type",
                "id",
                "attributes"=> [
                  "name",
                  "checklist"=> [
                    "description",
                    "due_interval",
                    "due_unit"
                  ],
                  "items"=> [
                    '*'=>[
                        "description",
                        "urgency",
                        "due_interval",
                        "due_unit"
                    ]
                  ]
                ],
                "links"=>[
                    "self"
                ]
              ]
        ]);
    }

    public function testTemplateUpdate(){
        $id = Template::get()->random()->id;
        $data = $this->dataTemplate();
        $this->patch('/checklists/templates/'.$id,$data,$this->header());
        $this->seeStatusCode(200);
        $this->addTemplateJson();
    }

    public function testTemplateDestroy(){
        // $id = 51;
        $id = Template::get()->random()->id;
        $this->delete('/checklists/templates/'.$id,[],$this->header());
        $this->seeStatusCode(204);

    }

    /**
     * Assign bulk checklists template by given templateId to many domains
     */
    public function testTemplateAssigns(){
        $id = Template::get()->random()->id;
        $checklist = Checklist::get();
        $data = $checklist->random(rand(0,count($checklist)))->pluck('object_domain','object_id')->map(function($item,$key){
            return [
                'attributes' => [
                    'object_domain' => $item,
                    'object_id' => $key,

                ]
            ];
        })->flatten(1)->toArray();

        $this->post('/checklists/templates/'.$id.'/assigns',$data,$this->header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            "meta"  =>[
                "count", "total"
            ],
            "data"  => [
                "*" => [
                    "type", "id",
                    "attributes" =>[
                        "object_domain",
                        "object_id",
                        "description",
                        "is_completed",
                        "due",
                        "urgency",
                        "completed_at",
                        "updated_by",
                        "created_by",
                        "created_at",
                        "updated_at",
                    ],
                    "links" =>[
                        "self"
                    ]
                ]
            ],
            "included" => [
                "*" => [
                    "type", "id",
                    "attributes"=>[
                        "description",
                        "is_completed",
                        "due",
                        "urgency",
                        "updated_by",
                        "user_id",
                        "checklist_id",
                        "deleted_at",
                        "created_at",
                        "updated_at",
                    ],
                    "links" =>[
                        "self"
                    ]
                ]
            ]
        ]);
    }

    public function dataTemplate(){
         /**
         * template data
         */
        $template = factory(Template::class)->make()->only('name');

        $unit = ['minute','hour','day','week','mounth'];
        
        /**
         * checklist data
         */
        $checklist = factory(Checklist::class)->make([
            'due_interval'=> rand(1,10),'due_unit'=>$unit[rand(0,4)]
        ])->only('description','due_interval','due_unit');
        
        /**
         * item data
         */
        $items = factory(Item::class,rand(1,3))->make([
            'due_interval'=> rand(1,10),'due_unit'=>$unit[rand(0,4)]
        ]);
        $items = $items->map(function($item){
            return[
                'due_interval' => $item['due_interval'],
                'due_unit' => $item['due_unit'],
                'urgency' => $item['urgency'],
                'description' => $item['description']
            ];
        })->toArray();

        /**
         * all data
         */
        $data['name']=$template['name'];
        $data['checklist']=$checklist;
        $data['items']= $items;

        return $data;
    }

    public function addTemplateJson(){
        $this->seeJsonStructure([
            "data"=> [
                "attributes"=> [
                  "name",
                  "checklist"=> [
                    "description",
                    "due_interval",
                    "due_unit"
                  ],
                  "items"=> [
                    '*'=>[
                        "description",
                        "urgency",
                        "due_interval",
                        "due_unit"
                    ]
                  ]
                ]
              ]
        ]);
    }
}
