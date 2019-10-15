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
        $this->get('/checklists/templates',[]);
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
        $this->post('/checklists/templates',$data,[]);
        $this->seeStatusCode(201);
        $this->addTemplateJson();
    }

    public function testTemplateShow(){
        $id = Template::get()->random()->id;
        $this->get('/checklists/templates/'.$id,[]);
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
        $this->patch('/checklists/templates/'.$id,$data,[]);
        $this->seeStatusCode(200);
        $this->addTemplateJson();
    }

    public function testTemplateDestroy(){
        // $id = 51;
        $id = Template::get()->random()->id;
        $this->delete('/checklists/templates/'.$id,[],[]);
        $this->seeStatusCode(204);

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
                ]
              ]
        ]);
    }
}
