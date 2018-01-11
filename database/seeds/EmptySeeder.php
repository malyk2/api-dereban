<?php

use Illuminate\Database\Seeder;

class EmptySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('groups')->truncate();
        DB::table('user_group')->truncate();
        DB::table('invite_notifies')->truncate();
        DB::table('invite_users')->truncate();
    }
}
