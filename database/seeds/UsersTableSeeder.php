<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create([
            'name' => 'methir',
            'email' => 'methir.game@gmail.com',
            'authority' => 2,
        ]);

        factory(App\User::class)->create([
            'name' => 'destrus',
            'email' => 'destrus_2011@hotmail.com',
            'authority' => 1,
        ]);

        factory(App\User::class, 58)->create();
    }
}