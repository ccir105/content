<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Advice;
use App\User;
use App\Repositories\AdviceRepository;
use App\Elastic\Advice as ElasticAdvice;

class ExampleTest extends TestCase
{

    // use DatabaseTransactions;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public $esAdvice;

    public $eqAdvice;

    public function setUp()
    {
        parent::setUp();
        $this->esAdvice = new ElasticAdvice;
        $this->eqAdvice = new Advice;
        $this->repo = new AdviceRepository($this->esAdvice, $this->eqAdvice);
    }

    public function createAdvice($opt = [])
    {
        $user = User::orderByRaw("RAND()")->first();

        $advice = Advice::setUserId($user->id);

        $this->repo->setEloquent($advice);

        $advice = $this->repo->save(array_merge([
            'content' => 'first advice',
            'priority' => '2'
        ],$opt));

        return $advice;
    }

    public function testNewAdviceEntry()
    {
        $advice = $this->createAdvice();

        $this->assertTrue(!Advice::all()->isEmpty());

        $this->assertEquals( $advice->id,  $this->esAdvice->find( $advice->id )->id );
    }

    public function testEditAdvice()
    {
        $advice = $this->createAdvice(['content'=>'new']);
        $editedAdvice = $this->repo->save(['content' => 'edited content'],$advice);
        $this->assertEquals($advice->content, $editedAdvice->content);
        $this->assertEquals($advice->user_id, $editedAdvice->user_id);
    }

    public function testDeleteAdvice()
    {
        $advice = $this->createAdvice(['content'=>'new']);
        
        $this->repo->delete($advice);
        
        $this->assertFalse( in_array($advice->id, $this->eqAdvice->all()->lists('id')->toArray()) );

        $this->assertFalse( in_array($advice->id, $this->esAdvice->all()->lists('id')->toArray()) );
    }
}
