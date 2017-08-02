<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'title' => 'Mr',
            'first_name'=>'Byron',
            'last_name'=>'Davies',
            'email'=>'byrondavies40@hotmail.com',
            'password' => bcrypt('secret1234$'),
            'user_role'=>'back',
            'activated'=>true,
            'organisation_id'=>1
        ]);

        DB::table('organisation_type')->insert([
            'type'=>'Conglomerate'
        ]);

        DB::table('organisation')->insert([
           'name'=>'Example',
            'al1'=>'eg',
            'town'=>'eg',
            'region'=>'eg',
            'postcode'=>'eg',
            'country'=>'eg',
            'company_type_id'=>1
        ]);

        DB::table('config')->insert([
            'key'=>'adm_org',
            'value'=>1
        ]);
    }
}
