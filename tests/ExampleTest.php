<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Advice;
use App\User;
use App\Repositories\AdviceRepository;
use App\Elastic\Advice as ElasticAdvice;
use Faker\Factory;
class ExampleTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public $repo;

    public $faker;

    public function setUp()
    {
        parent::setUp();

        $this->faker = Factory::create('ne_NP');

        $this->repo = app('PageRepo');
    }

    public function create($opt = [], $model = null)
    {
        return $model = $this->repo->save(array_merge([ 'name' => $this->faker->word ,'user_id' => 14 ], $opt),$model);
    }

    public function test_create_page()
    {
        $model = $this->create();
        
        $this->assertTrue($model->exists);
    }

    public function test_create_edit()
    {
        $model = $this->create();

        $newModel = $this->create(['name' => 'Test Page'],$model);

        $this->assertEquals('Test Page', $newModel->name);
    }

    public function test_delete_page()
    {
        $model = $this->create();

        $status = $this->repo->remove($model);
        
        $this->assertTrue($status);

        $this->assertFalse($model->exists);
    }

    public function getUserToken($id = 14)
    {
        $jwt = app(Tymon\JWTAuth\JWTAuth::class);
        $token = $jwt->fromUser( App\User::find($id) );

        return [ 'HTTP_AUTHORIZATION' => 'Bearer' .$token ];
    }

    public function test_create_page_api()
    {

        $token = $this->getUserToken();

        $createAdviceResponse = $this->call('POST','page', 
            [ 'name' => 'My First Page' ],[],[], 
            $token
        );

        $response = $this->getResponse($createAdviceResponse, true);

        $this->assertEquals('My First Page',$response['name']);
    }

    public function getResponse($response, $arrayConvert = false)
    {
        if(method_exists($response, 'getData'))
        {
            return $response->getData();
        }
        else
        {
            return $arrayConvert ? $response->original->toArray() : $response->original;
        }
    }

    public function test_edit_page_api()
    {
        $token = $this->getUserToken();

        $page = $this->create();

        $editRes = $this->call('POST','page/'.$page->id,['name' =>'API'],[],[],$token);

        $response = $this->getResponse($editRes, true);

        $this->assertEquals('API',$response['name']);

        $this->assertEquals($page->id, $response['id']);
    }

    public function test_delete_page_api()
    {
        $token = $this->getUserToken();

        $page = $this->create();

        $editRes = $this->call('DELETE','page/' . $page->id,[],[],[],$token);

        $response = $this->getResponse($editRes);

        $this->assertEquals('success', $response->status);
    }


    public function test_get_mine_api()
    {
        $token = $this->getUserToken();

        $mineRes = $this->call('GET','page', [],[],[],$token);

        $response = $this->getResponse($mineRes);

        $this->assertTrue(method_exists($response, 'isEmpty'));

        $this->assertTrue(is_numeric($response->count()));
    }

    // public function createAdvice($opt = [])
    // {
    //     $user = User::orderByRaw("RAND()")->first();

    //     $advice = Advice::setUserId($user->id);

    //     $this->repo->setEloquent($advice);

    //     $advice = $this->repo->save(array_merge([
    //         'content' => 'first advice',
    //         'priority' => '2'
    //     ],$opt));

    //     return $advice;
    // }

    // public function testNewAdviceEntry()
    // {
    //     $advice = $this->createAdvice();

    //     $this->assertTrue(!Advice::all()->isEmpty());

    //     $this->assertEquals( $advice->id,  $this->esAdvice->find( $advice->id )->id );
    // }

    // public function testEditAdvice()
    // {
    //     $advice = $this->createAdvice(['content'=>'new']);
    //     $editedAdvice = $this->repo->save(['content' => 'edited content'],$advice);
    //     $this->assertEquals($advice->content, $editedAdvice->content);
    //     $this->assertEquals($advice->user_id, $editedAdvice->user_id);
    // }

    // public function testDeleteAdvice()
    // {
    //     $advice = $this->createAdvice(['content'=>'new']);
        
    //     $this->repo->delete($advice);
        
    //     $this->assertFalse( in_array($advice->id, $this->eqAdvice->all()->lists('id')->toArray()) );

    //     $this->assertFalse( in_array($advice->id, $this->esAdvice->all()->lists('id')->toArray()) );
    // }
}
