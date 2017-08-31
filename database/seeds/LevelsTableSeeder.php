<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('levels')->insert([`id` => 1,
            `name` => 'hello',
            `level`=> 0,
            `sub_level`=> 0,
            `time` => 3600, ]);

        DB::table('levels')->insert([`id` => 2,
            `name` => 'question_1',
            `level`=> 1,
            `sub_level`=> 1,
            `time` => 86400, ]);

        DB::table('levels')->insert([`id` => 3,
            `name` => 'lambda',
            `level`=> 2,
            `sub_level`=> 1,
            `time` => 172800, ]);

        DB::table('levels')->insert([`id` => 4,
            `name` => 'dooms_day',
            `level`=> 2,
            `sub_level`=> 2,
            `time` => 172800, ]);
    }
}
