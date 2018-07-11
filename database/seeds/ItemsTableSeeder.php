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

        $users = App\User::with('rpgs.shops.items')->get();

        $users->each(function ($user) {
            $user->rpgs->each(function ($rpg) use ($user){
                $rpg->shops->each(function ($shop) use ($user){
                    $user->items()->attach([
                        $shop->items[0]->id => ['status' => true],
                    ]);
                });
            });
        });
    }
}
