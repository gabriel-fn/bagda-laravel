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
        App\Shop::find(1)->items()->saveMany([
            factory(App\Item::class)->make(['name' => 'Mercador']),
            factory(App\Item::class)->make(['name' => 'Alfaiate']),
            factory(App\Item::class)->make(['name' => 'Banqueiro']),
        ]);

        App\Shop::find(2)->items()->saveMany([
            factory(App\Item::class)->make(['name' => 'Espada']),
            factory(App\Item::class)->make(['name' => 'Arco']),
            factory(App\Item::class)->make(['name' => 'Adaga']),
        ]);

        App\Shop::find(3)->items()->saveMany([
            factory(App\Item::class)->make(['name' => 'Big Daddy']),
            factory(App\Item::class)->make(['name' => 'War Dog']),
            factory(App\Item::class)->make(['name' => 'Little Sister']),
        ]);

        App\Shop::find(4)->items()->saveMany([
            factory(App\Item::class)->make(['name' => 'Portal']),
            factory(App\Item::class)->make(['name' => 'Controle de Fogo']),
            factory(App\Item::class)->make(['name' => 'Cura']),
        ]);

        App\Shop::find(5)->items()->saveMany([
            factory(App\Item::class)->make(['name' => 'Arma Laser']),
            factory(App\Item::class)->make(['name' => 'Luvas Anti-Gravidade']),
            factory(App\Item::class)->make(['name' => 'Punhal de luz']),
        ]);

        App\Shop::find(6)->items()->saveMany([
            factory(App\Item::class)->make(['name' => 'Diplomacia']),
            factory(App\Item::class)->make(['name' => 'Desarmar dispositivo']),
            factory(App\Item::class)->make(['name' => 'Montaria']),
        ]);

        /*App\Shop::all()->each(function ($shop) {
            $shop->items()->saveMany(factory(App\Item::class, 3)->make());
        });*/

        $rpgs = App\Rpg::with(['players', 'shops.items'])->get();

        $players = $rpgs[0]->players()->get();
    
        foreach ($players as $player) {
            $player->items()->attach([
                $rpgs[0]->shops[rand(0,2)]->items[rand(0,2)]->id => [
                    'units' => 1
                ]
            ]);
        }

        $players = $rpgs[1]->players()->get();
    
        foreach ($players as $player) {
            $player->items()->attach([
                $rpgs[1]->shops[rand(0,2)]->items[rand(0,2)]->id => [
                    'units' => 1
                ]
            ]);
        }
    }
}
