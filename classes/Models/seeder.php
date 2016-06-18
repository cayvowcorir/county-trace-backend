<?php
namespace Models;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('citizen')->insert([
            'citizenName' => str_random(10),
            'citizenEmail' => str_random(10).'@gmail.com',
            'citizenPassword' => bcrypt('secret'),
        ]);
    }
}
