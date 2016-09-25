<?php

use App\Models\Link;
use App\Models\Click;
use Illuminate\Database\Seeder;

class ClicksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Link::all() as $link)
        {
            factory(Click::class, rand(0, 50))->create(['link_id' => $link->id]);
        }
    }
}
