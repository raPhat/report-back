<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Database\Eloquent\Collection;
use App\Services\UserService;
use App\Services\TaskService;
use App\Services\ProjectService;

class UserServiceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
//        User $user,
//        Notify $notify,
//        ProjectService $projectService,
//        TaskService $taskService
        $this->mockUser = $this->mock('App\Models\User');
        $this->mockNotify = $this->mock('App\Models\Notify');
        $this->mockTask = $this->mock('App\Models\Task');
        $this->mockTaskLog = $this->mock('App\Models\TaskLog');
        $this->mockProject = $this->mock('App\Models\Project');

        $taskService = new TaskService($this->mockTask, $this->mockTaskLog);
        $projectService = new ProjectService($this->mockProject);

        $this->repository = new UserService($this->mockUser, $this->mockNotify, $projectService, $taskService);
    }

    public function mock($class)
    {
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);
        return $mock;
    }

    public function test_get_user_by_id()
    {
        $this->mockUser->shouldReceive('with->where->first')->once()->andReturn(new Collection());
        $actual = $this->repository->getUserById(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

    public function test_get_notifies_by_user_id()
    {
        $this->mockUser->shouldReceive('with->where->first->toArray')->once()->andReturn([
            'notifies' => [
                [
                    'comment' => null,
                    'task_log' => ''
                ],
                [
                    'comment' => '',
                    'task_log' => null
                ]
            ]
        ]);
        $actual = $this->repository->getNotifiesByUserId(1);
        $this->assertEquals([
            [
                'comment' => null,
                'task_log' => ''
            ],
            [
                'comment' => '',
                'task_log' => null
            ]
        ], $actual);
        $this->assertEquals(2, count($actual));
    }

    public function test_my_statistic()
    {
        $this->mockProject->shouldReceive('with->where->get')->once()->andReturn([1, 2, 3]);
        $this->mockTask->shouldReceive('with->whereHas->orderBy->get')->once()->andReturn([1, 2]);
        $actual = $this->repository->getMyStatistic(1);
        $this->assertEquals([
            'total_projects' => 3,
            'total_tasks' => 2,
            'total_days' => 0
        ], $actual);
    }

    public function test_get_statistic_by_user_id()
    {
        $this->mockProject->shouldReceive('with->where->get')->once()->andReturn([1, 2, 3]);
        $this->mockTask->shouldReceive('with->whereHas->orderBy->get')->once()->andReturn([1, 2]);
        $actual = $this->repository->getStatisticByUserID(1);
        $this->assertEquals([
            'total_projects' => 3,
            'total_tasks' => 2,
            'total_days' => 0
        ], $actual);
    }

    public function test_get_user_by_code()
    {
        $this->mockUser->shouldReceive('where->first')->once()->andReturn(new Collection());
        $actual = $this->repository->getUserByCode('11111');
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

    public function test_get_mentors_by_student_id()
    {
        $this->mockUser->shouldReceive('find->Mentors')->once()->andReturn(new Collection());
        $actual = $this->repository->getMentorsByStudentId(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

    public function test_get_supervisors_by_student_id()
    {
        $this->mockUser->shouldReceive('find->Supervisors')->once()->andReturn(new Collection());
        $actual = $this->repository->getSupervisorsByStudentId(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

    public function test_set_user_of_student()
    {
        $this->mockUser->shouldReceive('find')->once()->andReturn(new Collection());
        $this->mockUser->shouldReceive('Users->sync')->once()->andReturn(new Collection());
        $this->mockUser->shouldReceive('save')->once()->andReturn(new Collection());
        $this->mockUser->shouldReceive('with->where')->once()->andReturn(new Collection());
        $actual = $this->repository->setUserOfStudent(1, 2);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }
}
