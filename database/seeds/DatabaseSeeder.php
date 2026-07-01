<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        require_once database_path('seeds/EdmsAccountsAndRolesSeeder.php');
        $this->call(EdmsAccountsAndRolesSeeder::class);
    }
}
