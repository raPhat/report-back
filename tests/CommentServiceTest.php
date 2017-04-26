<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Database\Eloquent\Collection;

class CommentServiceTest extends TestCase
{

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->mockComment = $this->mock('App\Models\Comment');
        $this->mockTask = $this->mock('App\Models\Task');

        $this->repository = new \App\Services\CommentService($this->mockComment, $this->mockTask);
    }

    public function mock($class)
    {
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);
        return $mock;
    }

    public function test_get_comment() {
        $this->mockComment->shouldReceive('with->where->first')->once()->andReturn(new Collection());
        $actual = $this->repository->getComment(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

    public function test_comments_by_task() {
        $this->mockTask->shouldReceive('with->where->first')->once()->andReturn(new Collection());
        $actual = $this->repository->getCommentsByTask(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

    public function test_comment() {
        $this->mockTask->shouldReceive('with->where->first')->once()->andReturn([
            'comments' => [
                [
                    'user_id' => 1
                ]
            ],
            'user_id' => 1,
            'Project' => [
                'user_id' => 1
            ]
        ]);

        $user = new \App\Models\User();
        $user->id = 1;

        $actual = $this->repository->comment([
            'text' => '',
            'task_id' => 1
        ], $user);
        $this->assertInstanceOf('App\Models\Comment', $actual);
    }

    public function test_destroy() {
        $this->mockComment->shouldReceive('destroy')->once()->andReturn(new Collection());
        $actual = $this->repository->destroy(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }
}