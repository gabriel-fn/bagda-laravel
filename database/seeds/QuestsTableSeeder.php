<?php

use Illuminate\Database\Seeder;

class QuestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = App\User::all();

        $users->each(function ($user) {
            $user->rpgs->each(function ($rpg) use ($user){
                if ($rpg->player->credential == 1) {
                    $quest = factory(App\Quest::class)->make(['rpg_id' => $rpg->id]);
                    $user->my_quests()->save($quest);
                    $quest->items()->attach($rpg->items[rand(0, 5)]);
                }
            });
        });

        $users = App\User::all();

        $users->each(function ($user) {
            $user->rpgs->each(function ($rpg) use ($user){
                if ($rpg->player->credential == 0) {
                    if ($rpg->quests) {
                        $user->quests()->attach($rpg->quests[0]->id);
                    }
                }
            });
        });
    }
}
