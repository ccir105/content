<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Created by PhpStorm.
 * User: sishir
 * View Thread Policy
 * Date: 3/7/16
 * Time: 4:55 PM
 */
class PolicyTest extends TestCase

{
    public function manager_policy($user_id, $project_id = 4, $thread_id = 170)
    {
        $user =  \App\User::find($user_id);

        $project =  \Modules\Project\Entities\Project::find( $project_id );

        \Modules\Threads\Policy\AdminPolicy::$project = $project;

        \Modules\Threads\Policy\AdminPolicy::$user = $user;

        $policy = new \Modules\Threads\Policy\AdminPolicy();

        $projectPolicy = new \Modules\Threads\Policy\ProjectPolicy();

        $threadPolicy = new \Modules\Threads\Policy\ThreadViewPolicy();

        $thread = \Modules\Threads\Entities\Thread::find( $thread_id );

        $threadPolicy->setThread($thread);

        $projectPolicy->setSuccessor( $threadPolicy );

        $policy->setSuccessor( $projectPolicy );

        return $policy->handle('');
    }

    public function test_developer_thread_policy()
    {
        $value = $this->manager_policy(299 , 4 , 170);

        $this->assertEquals(null,$value);
    }

    public function test_manager_thread_policy()
    {
        $value = $this->manager_policy(298, 4);

        $this->assertEquals(true,$value);

    }

    public function test_client_thread_policy()
    {
        //user id, project_id, thread_id
        $value = $this->manager_policy(42, 4, 170);

        $this->assertEquals(true, $value);

        $value = $this->manager_policy(298, 4, 170);
    }

    public function test_admin_thread_policy()
    {
        $value = $this->manager_policy(45);
        $this->assertEquals(true, $value);
    }
}