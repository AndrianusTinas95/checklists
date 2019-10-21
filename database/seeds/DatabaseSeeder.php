<?php

use App\Checklist;
use App\History;
use App\Item;
use App\Template;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');
        factory(User::class,10)->create();
        factory(Template::class,50)->create()->each(function($temp){
            $temp->checklist()->save(factory(Checklist::class)->make());
            $temp->items()->saveMany(factory(Item::class,rand(1,5))->make([
                'assignee_id'   => $temp->id
            ]));
        });
        factory(History::class,200)->create();
    }
}
