<?php

use Illuminate\Database\Seeder;

class ShopsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $solarion = App\Rpg::find(1);
        $solarion->shops()->saveMany([
            factory(App\Shop::class)->make([
                'name' => 'Profissões',
                'is_multiple_sale' => false,
            ]),
            factory(App\Shop::class)->make([
                'name' => 'Armas',
            ]),
            factory(App\Shop::class)->make([
                'name' => 'Automatos',
            ]),
        ]);

        $alika = App\Rpg::find(2);
        $alika->shops()->saveMany([
            factory(App\Shop::class)->make([
                'name' => 'Poderes',
                'is_multiple_sale' => false,
            ]),
            factory(App\Shop::class)->make([
                'name' => 'Equipamentos',
            ]),
            factory(App\Shop::class)->make([
                'name' => 'Pericias',
            ]),
        ]);
    }
}
