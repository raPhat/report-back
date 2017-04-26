<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Database\Eloquent\Collection;

class TaskServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->mockTask = $this->mock('App\Models\Task');
        $this->mockTaskLog = $this->mock('App\Models\TaskLog');

        $this->repository = new \App\Services\TaskService($this->mockTask, $this->mockTaskLog);
    }

    public function mock($class)
    {
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);
        return $mock;
    }

    public function test_store()
    {
        $this->mockTask->shouldReceive('with->where->first')->andReturn(new Collection());

        $request = new \App\Http\Requests\StoreTaskPost();
        $request->name = '';
        $request->description = '';
        $request->project_id = 1;
        $request->start = [
            'epoc' => 1493031284
        ];
        $actual = $this->repository->store($request);
        print_r($actual->toArray());
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

    public function test_get_task_by_task_id()
    {
        $this->mockTask->shouldReceive('with->where->first')->once()->andReturn($this->mockTask);
        $actual = $this->repository->getTaskByTaskID(1);
        $this->assertInstanceOf('App\Models\Task', $actual);
    }
}
