<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadRepoTest extends TestCase
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

        $user = \App\User::find(298);

        $repo = new \Modules\Threads\Repositories\ThreadRepo();

        $repo->setUser($user);

        $repo->setProject(\Modules\Project\Entities\Project::find(2));

        $title = $faker->sentence(3);

        $data = array_merge([
            'title' => $title,
            'description' => $faker->paragraph,
            'status' => '1',
            'type' => 'bug'
        ],$data);

       return $repo->save($data);

    }


    public function _new_thread()
    {
        $faker = Faker\Factory::create();

        $repo = new \Modules\Threads\Repositories\ThreadRepo();

        $user = \App\User::find(298);

        $project = \Modules\Project\Entities\Project::find(2);

        $repo->setUser($user);

        $repo->setProject($project);

        $title = $faker->sentence(3);

        $data = [
            'title' => $title,
            'description' => $faker->paragraph,
            'status' => '1',
            'type' => 'bug'
        ];

        $repo->save($data);

        $thread = $user->threads->first();

        $nextWay = $project->threads->first();

        $this->assertEquals($nextWay->title, $thread->title);
        $this->assertEquals($title, $thread->title);
    }

    public function test_delete(){

        $thread = $this->create();

        $repo = new \Modules\Threads\Repositories\ThreadRepo();

        $repo->delete($thread);

        $this->assertFalse($repo->find($thread->id));
    }

    public function test_find(){
        $thread = $this->create();

        $repo = new \Modules\Threads\Repositories\ThreadRepo();

        $findThread = $repo->find($thread->id);

        $this->assertEquals($thread->title,$findThread->title);

    }

    public function test_edit_thread(){
        $repo = new \Modules\Threads\Repositories\ThreadRepo();

        $thread = $this->create();

        $repo->edit([
            'status' => '2',
            'title' => 'My Title',
            'type' => 'information'
        ], $thread);

        $t = $repo->find($thread->id);

        $this->assertEquals($t->title, 'My Title');

        $this->assertEquals($t->status, '2');
    }

    public function test_thread_comment(){
        $repo = new \Modules\Threads\Repositories\CommentRepo();

        $faker = Faker\Factory::create();

        $thread = $this->create();

        $user = \App\User::find(298);

        $repo->setUser($user);

        $repo->save( ['text' => $faker->sentence ],$thread);

        $repo->save( ['text' => $faker->sentence ],$thread);

        $threadrepo = new \Modules\Threads\Repositories\ThreadRepo();

        $thread = $threadrepo->find( $thread->id );

        $this->assertEquals(2,$thread->comments->count());

        $repo->delete($thread->comments->first());

        $threadrepo = new \Modules\Threads\Repositories\ThreadRepo();

        $thread = $threadrepo->find( $thread->id );

        $this->assertEquals(1,$thread->comments->count());

        $comment = $thread->comments->first();

        $comment = $repo->edit(['text'=>'My Text'],$comment,$thread);

        $thread = $threadrepo->find( $thread->id );

        $commentFirst = $thread->comments->first();

        $this->assertEquals($commentFirst->text, 'My Text');
    }
}
