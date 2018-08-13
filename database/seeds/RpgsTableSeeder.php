<?php

use Illuminate\Database\Seeder;

class RpgsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $methir = App\User::find(1);
        $methir->my_rpgs()->save(
            factory(App\Rpg::class)->make([
                'name' => 'Solarion', 
                'is_public' => false
            ])
        );

        $destrus = App\User::find(2);
        $destrus->my_rpgs()->save(
            factory(App\Rpg::class)->make([
                'name' => 'Alika'
            ])
        );

        App\User::all()->each(function ($user){
            $rpg_id = ($user->id < 31)?1:2;
            $user->rpgs()->attach([
                $rpg_id => [
                    'credential' => rand(0, 1),
                    'gold' => rand(1000, 2000),
                    'cash' => rand(100, 1000),
                    'detail' => 'bulhufas para definir o personagem',
                ]
            ]);
        });
    }
}
