<?php

use Illuminate\Database\Seeder;
use App\UserAdvice;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Advice::class, 300)->create()->each(function($advice){
        	$pr = [1,2,3,4];
        	$userAdvice = new UserAdvice;
        	$userAdvice->advice_id = $advice->id;
        	$userAdvice->user_id = 1;
        	$userAdvice->priority = $pr [ array_rand( $pr ) ];
        	$userAdvice->save();
        });
    }
}
