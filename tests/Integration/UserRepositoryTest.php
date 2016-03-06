<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserFactoryTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */

    use DatabaseTransactions;

    protected $repo;


    public function setUp()
    {
        parent::setUp();
    }

    public function create($data = array()){

        $faker = Faker\Factory::create();

        $user = array_merge([
            'name' => $faker->name,
            'email' => $faker->email,
            'address' => $faker->address,
            'phone' => $faker->phoneNumber,
            'password' => Hash::make('test')
        ],$data);

        $savedUser = $this->repo->create($user);

        return $savedUser;
    }

    /**
     *
    public function testCreate()
    {
        $role = \Modules\UserManagement\Entities\Role::whereName('admin')->first();

        $user = $this->create( ['roles' => $role->id ]);

        $this->assertEquals($user['name'] , $user->name);

        $this->assertTrue($user->hasRole($role->name));
    }

    public function test_all(){

        $this->create();

        $this->create();

        $this->assertEquals(2,$this->repo->all()->count());
    }
     */

    public function test_duplicate_project(){

        $project = \Modules\Project\Entities\Project::find(4);

        $duplicateProject = $project->duplicate();

        $this->assertEquals($project->pages->count(),$duplicateProject->pages->count());

        $duplicatePage = $duplicateProject->pages->first();

        $orginalPage = $project->pages->first();

        $this->assertEquals($duplicatePage->fields->count(),$orginalPage->fields->count());
    }

    public function test_user(){
        $faker = Faker\Factory::create();
        $user = new App\Elastic\User();
        $user->name = $faker->name;
        $user->email = $faker->email;
        $user->save();

        $conn = $user->elasticConnection;

        $query = new \Elastica\Query\Ids();

        $query->setIds([52, 53]);

        $result = $conn->search($query);
    }

    public function test_where_clause(){
        $faker = Faker\Factory::create();
        $user = new App\Elastic\User;
        $result = $user->whereLike(['name','email'],'Br')->search();
        echo PHP_EOL;
        foreach($result as $re){
            echo $re->name . PHP_EOL;
        }

//        echo $result->count();

//        print_r($user->search());
    }
}
