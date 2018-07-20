<?php

use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Shop::all()->each(function ($shop) {
            $shop->items()->saveMany(factory(App\Item::class, 3)->make());
        });

        $rpgs = App\Rpg::with(['players', 'shops.items'])->get();

        $players = $rpgs[0]->players()->get();
    
        foreach ($players as $player) {
            $player->items()->attach([
                $rpgs[0]->shops[rand(0,2)]->items[rand(0,2)]->id => ['status' => rand(0,1)]
            ]);
        }

        $players = $rpgs[1]->players()->get();
    
        foreach ($players as $player) {
            $player->items()->attach([
                $rpgs[1]->shops[rand(0,2)]->items[rand(0,2)]->id => ['status' => rand(0,1)]
            ]);
        }
    }
}
