<?php

use Illuminate\Database\Seeder;
use App\UserAdvice;
use App\Elastic\Advice;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\User::class, 2)->create();

        factory(App\Advice::class, 20)->create()->each(function($advice){
        	Advice::create($advice->toArray());
        });
    }
}
