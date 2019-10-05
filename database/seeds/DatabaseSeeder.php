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
        factory(User::class,10);
        factory(Template::class,50);
        factory(Checklist::class,100);
        factory(Item::class,200);
        factory(History::class,200);
    }
}
