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
        $this->repo = new Modules\Management\Repositories\UserRepository();
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

    public function testCreate()
    {
        $role = \Modules\Management\Entities\Role::whereName('admin')->first();

        $user = $this->create( ['roles' => $role->id ]);

        $this->assertEquals($user['name'] , $user->name);

        $this->assertTrue($user->hasRole($role->name));
    }

    public function test_all(){

        $this->create();

        $this->create();

        $this->assertEquals(2,$this->repo->all()->count());
    }

    public function test_all_user_contain_roles(){

        $role1 = \Modules\Management\Entities\Role::whereName('admin')->first();

        $role2 = \Modules\Management\Entities\Role::whereName('client')->first();

        $this->create();

        $this->create();
    }
}
