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
    use DatabaseTransactions;

    public $mockStudentData = [
        'first_name' => '',
        'last_name' => '',
        'description' => '',
        'email' => 'tester@test.com',
        'company' => '',
        'position' => '',
        'password' => '123456',
        'avatar' => '',
        'role' => 'student',
        'start' => [
            'epoc' => 1493031284
        ]
    ];
    public $mockMentorData = [
        'first_name' => '',
        'last_name' => '',
        'description' => '',
        'email' => 'mentor@test.com',
        'company' => '',
        'position' => '',
        'password' => '123456',
        'avatar' => '',
        'role' => 'mentor',
        'start' => [
            'epoc' => 1493031284
        ]
    ];
    public $mockSupervisorData = [
        'first_name' => '',
        'last_name' => '',
        'description' => '',
        'email' => 'supervisor@test.com',
        'company' => '',
        'position' => '',
        'password' => '123456',
        'avatar' => '',
        'role' => 'supervisor',
        'start' => [
            'epoc' => 1493031284
        ]
    ];

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

//        $taskService = new TaskService($this->mockTask, $this->mockTaskLog);
//        $projectService = new ProjectService($this->mockProject);
        $this->mockTaskService = $this->mock('App\Services\TaskService');
        $this->mockProjectService = $this->mock('App\Services\ProjectService');

        $this->repository = new UserService($this->mockUser, $this->mockNotify, $this->mockProjectService, $this->mockTaskService);
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
//        $this->mockProject->shouldReceive('with->where->get')->once()->andReturn([1, 2, 3]);
//        $this->mockTask->shouldReceive('with->whereHas->orderBy->get')->once()->andReturn([1, 2]);
        $this->mockProjectService->shouldReceive('getProjectsByUserID')->once()->andReturn([1, 2, 3]);
        $this->mockTaskService->shouldReceive('getTasksByUserID')->once()->andReturn([1, 2]);
        $actual = $this->repository->getMyStatistic(1);
        $this->assertEquals([
            'total_projects' => 3,
            'total_tasks' => 2,
            'total_days' => 0
        ], $actual);
    }

    public function test_get_statistic_by_user_id()
    {
//        $this->mockProject->shouldReceive('with->where->get')->once()->andReturn([1, 2, 3]);
//        $this->mockTask->shouldReceive('with->whereHas->orderBy->get')->once()->andReturn([1, 2]);
        $this->mockProjectService->shouldReceive('getProjectsByUserID')->once()->andReturn([1, 2, 3]);
        $this->mockTaskService->shouldReceive('getTasksByUserID')->once()->andReturn([1, 2]);
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
        $this->mockUser->shouldReceive('find')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('Users->sync')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('getAttribute')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('save')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('with->where')->once()->andReturn(new Collection());
        $actual = $this->repository->setUserOfStudent(1, 2);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

    public function test_delete_user_of_student()
    {
        $this->mockUser->shouldReceive('find')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('Users->detach')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('getAttribute')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('save')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('with->where')->once()->andReturn(new Collection());
        $actual = $this->repository->deleteUserOfStudent(1, 2);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

    public function test_create()
    {
        $actual = $this->repository->create(['role' => 'teacher']);
        $this->assertNull($actual);

        $this->mock_set_detail();
        $actual = $this->repository->create($this->mockStudentData);
        $this->assertInstanceOf('App\Models\User', $actual);
        $this->assertEquals('student', $actual->role);
        $this->assertEquals('2017-04-24', $actual->start);
        $this->assertNotEquals('123456', $actual->password);

        $actual = $this->repository->create($this->mockMentorData);
        $this->assertInstanceOf('App\Models\User', $actual);
        $this->assertEquals('mentor', $actual->role);
        $this->assertNull($actual->start);
        $this->assertNotEquals('123456', $actual->password);

        $actual = $this->repository->create($this->mockSupervisorData);
        $this->assertInstanceOf('App\Models\User', $actual);
        $this->assertEquals('supervisor', $actual->role);
        $this->assertNull($actual->start);
        $this->assertNotEquals('123456', $actual->password);
    }

    public function test_create_student()
    {
        $this->mock_set_detail();
        $actual = $this->repository->createStudent($this->mockStudentData);
        $this->assertInstanceOf('App\Models\User', $actual);
        $this->assertEquals('student', $actual->role);
        $this->assertEquals('2017-04-24', $actual->start);
        $this->assertNotEquals('123456', $actual->password);
    }

    public function test_create_mentor()
    {
        $this->mock_set_detail();
        $actual = $this->repository->createMentor($this->mockMentorData);
        $this->assertInstanceOf('App\Models\User', $actual);
        $this->assertEquals('mentor', $actual->role);
        $this->assertNull($actual->start);
        $this->assertNotEquals('123456', $actual->password);
    }

    public function test_create_supervisor()
    {
        $this->mock_set_detail();
        $actual = $this->repository->createSupervisor($this->mockSupervisorData);
        $this->assertInstanceOf('App\Models\User', $actual);
        $this->assertEquals('supervisor', $actual->role);
        $this->assertNull($actual->start);
        $this->assertNotEquals('123456', $actual->password);
    }

    public function test_update()
    {
        $this->mockUser->shouldReceive('find')->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('setAttribute')->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('update')->andReturn(new Collection());
        $this->mock_set_detail();
        $actual = $this->repository->update($this->mockStudentData, 1);
//        [
//            'first_name' => '',
//            'last_name' => '',
//            'description' => '',
//            'email' => 'tester@test.com',
//            'company' => '',
//            'position' => '',
//            'role' => 'student',
//            'start' => [
//                'epoc' => 1490227200
//            ]
//        ]
        $this->assertInstanceOf('App\Models\User', $actual);
    }

    function mock_set_detail() {
        $this->mockUser->shouldReceive('getAttribute')->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('save')->andReturn(new Collection());
    }

    public function test_with_full()
    {
        $this->mockUser->shouldReceive('getAttribute')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('with->where')->once()->andReturn(new Collection());
        $actual = $this->repository->withFull($this->mockUser);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

    // getReportsByUserId
    public function test_get_reports_by_user_id()
    {
        $this->mockProjectService->shouldReceive('getMyProjectsByUserID')->andReturn([
            [
                'id' => 1,
                'reports' => []
            ],
            [
                'id' => 2,
                'reports' => []
            ]
        ]);
        $this->mockTaskService->shouldReceive('getTaskLogsByProjectIdAndDates')->andReturn(new Collection());
        $mockLog = new MockLog();
        $actual = $this->repository->getReportsByUserId(1, [$mockLog, $mockLog, $mockLog]);
        $this->assertCount(2, $actual);
        $this->assertArrayHasKey('reports' ,$actual[0]);
        $this->assertArrayHasKey('reports' ,$actual[1]);
        print_r($actual[0]['reports']);
//        $this->assertArrayHasKey('start' ,$actual[0]['reports'][0]);
//        $this->assertArrayHasKey('end' ,$actual[0]['reports'][0]);
    }
}

class MockLog {
    public $start = '';
    public $end = '';
}
