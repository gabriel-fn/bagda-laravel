<?php

use Illuminate\Database\Seeder;

class ReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $solarion = App\Rpg::find(1);
        $solarion->reports()->saveMany(factory(App\Report::class, 3)->make());

        $alika = App\Rpg::find(2);
        $alika->reports()->saveMany(factory(App\Report::class, 3)->make());
    }
}
