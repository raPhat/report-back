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
        $this->mockComment->shouldReceive('with->where')->with('id', 1)->once()->andReturn($this->mockComment);
        $this->mockComment->shouldReceive('first')->once()->andReturn($this->mockComment);
        $actual = $this->repository->getComment(1);
        $this->assertInstanceOf('App\Models\Comment', $actual);

        $this->mockComment->shouldReceive('with->where')->with('id', 99)->once()->andReturn($this->mockComment);
        $this->mockComment->shouldReceive('first')->once()->andReturnNull();
        $actual = $this->repository->getComment(99);
        $this->assertNull($actual);

        $actual = $this->repository->getComment('string');
        $this->assertFalse($actual);

        $actual = $this->repository->getComment(null);
        $this->assertFalse($actual);
    }

    public function test_comments_by_task() {
        $this->mockTask->shouldReceive('with->where')->with('id', 1)->once()->andReturn($this->mockTask);
        $this->mockTask->shouldReceive('first')->once()->andReturn(new Collection());
        $actual = $this->repository->getCommentsByTask(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);

        $this->mockTask->shouldReceive('with->where')->with('id', 99)->once()->andReturn($this->mockTask);
        $this->mockTask->shouldReceive('first')->once()->andReturnNull();
        $actual = $this->repository->getCommentsByTask(99);
        $this->assertNull($actual);

        $actual = $this->repository->getCommentsByTask('string');
        $this->assertFalse($actual);

        $actual = $this->repository->getCommentsByTask(null);
        $this->assertFalse($actual);
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

        $actual = $this->repository->comment([
            'text' => ''
        ], $user);
        $this->assertFalse($actual);

        $actual = $this->repository->comment([
            'task_id' => 1
        ], $user);
        $this->assertFalse($actual);

        $actual = $this->repository->comment([], $user);
        $this->assertFalse($actual);

        $actual = $this->repository->comment(null, null);
        $this->assertFalse($actual);
    }

    public function test_destroy() {
        $this->mockComment->shouldReceive('destroy')->once()->andReturn($this->mockComment);
        $actual = $this->repository->destroy(1);
        $this->assertInstanceOf('App\Models\Comment', $actual);

        $this->mockComment->shouldReceive('destroy')->with(99)->once()->andReturnNull();
        $actual = $this->repository->destroy(99);
        $this->assertNull($actual);

        $actual = $this->repository->destroy('string');
        $this->assertFalse($actual);

        $actual = $this->repository->destroy(null);
        $this->assertFalse($actual);
    }
}