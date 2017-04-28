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
        $this->mockUser = $this->mock('App\Models\User');

        $this->repository = new \App\Services\TaskService($this->mockTask, $this->mockTaskLog, $this->mockUser);
    }

    public function mock($class)
    {
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);
        return $mock;
    }

    public function test_store()
    {
        $this->mockTask->shouldReceive('with->where->first')->andReturn($this->mockTask);

        $request = new \App\Http\Requests\StoreTaskPost();
        $request->name = '';
        $request->description = '';
        $request->project_id = 1;
        $request->start = [
            'epoc' => 1493031284
        ];
        $actual = $this->repository->store($request);
        $this->assertInstanceOf('App\Models\Task', $actual);

        $request = new \App\Http\Requests\StoreTaskPost();
        $request->name = '';
        $request->description = '';
        $request->project_id = 1;
        $actual = $this->repository->store($request);
        $this->assertFalse($actual);

        $request = new \App\Http\Requests\StoreTaskPost();
        $request->name = '';
        $request->description = '';
        $actual = $this->repository->store($request);
        $this->assertFalse($actual);

        $request = new \App\Http\Requests\StoreTaskPost();
        $request->name = '';
        $actual = $this->repository->store($request);
        $this->assertFalse($actual);

        $request = new \App\Http\Requests\StoreTaskPost();
        $actual = $this->repository->store($request);
        $this->assertFalse($actual);
    }

    public function test_update()
    {
        $this->mockTask->shouldReceive('setAttribute')->andReturn($this->mockTask);
        $this->mockTask->shouldReceive('save')->andReturn(new Collection());
        $this->mockTask->shouldReceive('with->where->first')->once()->andReturn($this->mockTask);

        $request = new \App\Http\Requests\StoreTaskPost();
        $request->name = '';
        $request->description = '';
        $actual = $this->repository->update($request, 1);
        $this->assertInstanceOf('App\Models\Task', $actual);

        $request = new \App\Http\Requests\StoreTaskPost();
        $request->name = '';
        $actual = $this->repository->update($request, 1);
        $this->assertFalse($actual);

        $request = new \App\Http\Requests\StoreTaskPost();
        $actual = $this->repository->update($request, 1);
        $this->assertFalse($actual);
    }

    public function test_get_task_logs_by_project_id_and_dates()
    {
        $this->mockTaskLog->shouldReceive('with->whereHas->whereHas->get')->andReturn(new Collection());

        $actual = $this->repository->getTaskLogsByProjectIdAndDates(1, 1493031284, 1493031284);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);

        $actual = $this->repository->getTaskLogsByProjectIdAndDates(1, 'string', 'string');
        $this->assertFalse($actual);

        $actual = $this->repository->getTaskLogsByProjectIdAndDates('string', 1493031284, '1493031284');
        $this->assertFalse($actual);

        $actual = $this->repository->getTaskLogsByProjectIdAndDates(null, null, null);
        $this->assertFalse($actual);
    }

    public function test_get_task_logs_by_users()
    {
        $this->mockTaskLog->shouldReceive('with->whereHas->orderBy->get')->andReturn(new Collection());

        $user = new \App\Models\User();
        $user->id = 8;
        $actual = $this->repository->getTaskLogsByUsers([$user]);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);

        $actual = $this->repository->getTaskLogsByUsers([]);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);

        $actual = $this->repository->getTaskLogsByUsers(null);
        $this->assertFalse($actual);
    }

    public function test_get_task_logs_by_user_id()
    {
        $this->mockTaskLog->shouldReceive('with->whereHas->orderBy->get')->andReturn(new Collection());

        $actual = $this->repository->getLogsByUserID(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);

        $actual = $this->repository->getLogsByUserID('string');
        $this->assertFalse($actual);

        $actual = $this->repository->getLogsByUserID(null);
        $this->assertFalse($actual);
    }

    public function test_get_task_by_task_id()
    {
        $this->mockTask->shouldReceive('with->where')->with('id', 1)->once()->andReturn($this->mockTask);
        $this->mockTask->shouldReceive('first')->once()->andReturn($this->mockTask);
        $actual = $this->repository->getTaskByTaskID(1);
        $this->assertInstanceOf('App\Models\Task', $actual);

        $this->mockTask->shouldReceive('with->where')->with('id', 99)->once()->andReturn($this->mockTask);
        $this->mockTask->shouldReceive('first')->once()->andReturnNull();
        $actual = $this->repository->getTaskByTaskID(99);
        $this->assertNull($actual);

        $actual = $this->repository->getTaskByTaskID('string');
        $this->assertFalse($actual);

        $actual = $this->repository->getTaskByTaskID(null);
        $this->assertFalse($actual);
    }

    public function test_get_tasks_by_user_id()
    {
        $this->mockTask->shouldReceive('with->whereHas->orderBy->get')->once()->andReturn(new Collection());
        $actual = $this->repository->getTasksByUserID(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);

        $actual = $this->repository->getTasksByUserID('string');
        $this->assertFalse($actual);

        $actual = $this->repository->getTasksByUserID(null);
        $this->assertFalse($actual);
    }

    public function test_change_to()
    {
        $this->mockTask->shouldReceive('setAttribute')->andReturn($this->mockTask);
        $this->mockTask->shouldReceive('getAttribute')->andReturn($this->mockTask);
        $this->mockUser->shouldReceive('with->where->first')->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('toArray')->andReturn([
            'mentors' => [
                [
                    'id' => 4
                ]
            ],
            'supervisors' => []
        ]);
        $this->mockUser->shouldReceive('offsetGet')->andReturn($this->mockUser);

        $task = new \App\Models\Task();
        $task->id = 3;
        $task->name = 'Task Test';
        $task->description = 'Description Task Test';
        $task->project_id = 1;

        $this->mockTask->shouldReceive('with->where->first')->andReturn($task);
        $this->mockTaskLog->shouldReceive('where->delete')->andReturn($this->mockTaskLog);
        $this->mockTaskLog->shouldReceive('getAttribute')->with('task_id')->andReturn(1);
        $this->mockTaskLog->shouldReceive('save')->andReturn($this->mockTaskLog);
        $request = new MockRequest();
        $actual = $this->repository->changeTo($request, 1);
        $this->assertInstanceOf('App\Models\Task', $actual);
        $this->assertEquals(3, $actual->id);
        $this->assertEquals('Task Test', $actual->name);
        $this->assertEquals('ToDo', $actual->type->name);
        $this->assertEquals('Description Task Test', $actual->description);
        $this->assertEquals(1, $actual->project_id);

        unset($task['notify_ids']);
        $request = new MockRequest();
        $request->type = 'Doing';
        $actual = $this->repository->changeTo($request, 1);
        $this->assertInstanceOf('App\Models\Task', $actual);
        $this->assertEquals(2, $actual->task_type_id);

        unset($task['notify_ids']);
        $request = new MockRequest();
        $request->type = 'Done';
        $actual = $this->repository->changeTo($request, 1);
        $this->assertInstanceOf('App\Models\Task', $actual);
        $this->assertEquals(3, $actual->task_type_id);
    }
}

class MockRequest {
    public $type = 'ToDo';
    public $start = [
        'epoc' => 1493031284
    ];

    function user() {
        $user = new \App\Models\User();
        $user->id = 1;
        return $user;
    }
}