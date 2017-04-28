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
        'first_name' => 'student',
        'last_name' => 'ja',
        'description' => 'i\'m student',
        'email' => 'student@mail.com',
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
        'first_name' => 'mentor',
        'last_name' => 'ja',
        'description' => 'i\'m mentor',
        'email' => 'mentor@mail.com',
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
        'first_name' => 'supervisor',
        'last_name' => 'ja',
        'description' => 'i\'m supervisor',
        'email' => 'supervisor@mail.com',
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
        $this->mockUser->shouldReceive('with->where')->with('id', 1)->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('first')->once()->andReturn($this->mockUser);
        $actual = $this->repository->getUserById(1);
        $this->assertInstanceOf('App\Models\User', $actual);

        $this->mockUser->shouldReceive('with->where')->with('id', 99)->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('first')->once()->andReturnNull();
        $actual = $this->repository->getUserById(99);
        $this->assertNull($actual);

        $this->mockUser->shouldReceive('with->where')->with('id', 'string')->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('first')->once()->andReturnNull();
        $actual = $this->repository->getUserById('string');
        $this->assertNull($actual);

        $this->mockUser->shouldReceive('with->where')->with('id', null)->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('first')->once()->andReturnNull();
        $actual = $this->repository->getUserById(null);
        $this->assertNull($actual);
    }

    public function test_get_notifies_by_user_id()
    {
        $this->mockUser->shouldReceive('with->where')->with('id', 1)->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('first->toArray')->once()->andReturn([
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


        $this->mockUser->shouldReceive('with->where')->with('id', 99)->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('first->toArray')->once()->andReturn([
            'notifies' => []
        ]);
        $actual = $this->repository->getNotifiesByUserId(99);
        $this->assertEquals(0, count($actual));

        $actual = $this->repository->getNotifiesByUserId('string');
        $this->assertFalse($actual);

        $actual = $this->repository->getNotifiesByUserId(null);
        $this->assertFalse($actual);

    }

    public function test_my_statistic()
    {
        $this->mockProjectService->shouldReceive('getProjectsByUserID')->with(8)->once()->andReturn([2]);
        $this->mockTaskService->shouldReceive('getTasksByUserID')->with(8)->once()->andReturn([1, 2, 3, 4, 5, 6, 9]);
        $actual = $this->repository->getMyStatistic(8);
        $this->assertEquals([
            'total_projects' => 1,
            'total_tasks' => 7,
            'total_days' => 0
        ], $actual);

        $this->mockProjectService->shouldReceive('getProjectsByUserID')->with(1)->once()->andReturn([1]);
        $this->mockTaskService->shouldReceive('getTasksByUserID')->with(1)->once()->andReturn([]);
        $actual = $this->repository->getMyStatistic(1);
        $this->assertEquals([
            'total_projects' => 1,
            'total_tasks' => 0,
            'total_days' => 0
        ], $actual);

        $this->mockProjectService->shouldReceive('getProjectsByUserID')->andReturnNull();
        $this->mockTaskService->shouldReceive('getTasksByUserID')->andReturnNull();
        $actual = $this->repository->getMyStatistic(99);
        $this->assertEquals([
            'total_projects' => 0,
            'total_tasks' => 0,
            'total_days' => 0
        ], $actual);
        $actual = $this->repository->getMyStatistic('string');
        $this->assertEquals([
            'total_projects' => 0,
            'total_tasks' => 0,
            'total_days' => 0
        ], $actual);
        $actual = $this->repository->getMyStatistic(null);
        $this->assertEquals([
            'total_projects' => 0,
            'total_tasks' => 0,
            'total_days' => 0
        ], $actual);
    }

    public function test_get_statistic_by_user_id()
    {
        $this->mockProjectService->shouldReceive('getProjectsByUserID')->with(8)->once()->andReturn([2]);
        $this->mockTaskService->shouldReceive('getTasksByUserID')->with(8)->once()->andReturn([1, 2, 3, 4, 5, 6, 9]);
        $actual = $this->repository->getMyStatistic(8);
        $this->assertEquals([
            'total_projects' => 1,
            'total_tasks' => 7,
            'total_days' => 0
        ], $actual);

        $this->mockProjectService->shouldReceive('getProjectsByUserID')->with(1)->once()->andReturn([1]);
        $this->mockTaskService->shouldReceive('getTasksByUserID')->with(1)->once()->andReturn([]);
        $actual = $this->repository->getMyStatistic(1);
        $this->assertEquals([
            'total_projects' => 1,
            'total_tasks' => 0,
            'total_days' => 0
        ], $actual);

        $this->mockProjectService->shouldReceive('getProjectsByUserID')->andReturnNull();
        $this->mockTaskService->shouldReceive('getTasksByUserID')->andReturnNull();
        $actual = $this->repository->getMyStatistic(99);
        $this->assertEquals([
            'total_projects' => 0,
            'total_tasks' => 0,
            'total_days' => 0
        ], $actual);
        $actual = $this->repository->getMyStatistic('string');
        $this->assertEquals([
            'total_projects' => 0,
            'total_tasks' => 0,
            'total_days' => 0
        ], $actual);
        $actual = $this->repository->getMyStatistic(null);
        $this->assertEquals([
            'total_projects' => 0,
            'total_tasks' => 0,
            'total_days' => 0
        ], $actual);
    }

    public function test_get_user_by_code()
    {
        $this->mockUser->shouldReceive('where')->with('code', '16790')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('first')->once()->andReturn($this->mockUser);
        $actual = $this->repository->getUserByCode('16790');
        $this->assertInstanceOf('App\Models\User', $actual);

        $this->mockUser->shouldReceive('where')->with('code', '11111')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('first')->once()->andReturnNull();
        $actual = $this->repository->getUserByCode('11111');
        $this->assertNull($actual);

        $this->mockUser->shouldReceive('where')->with('code', null)->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('first')->once()->andReturnNull();
        $actual = $this->repository->getUserByCode(null);
        $this->assertNull($actual);
    }

    public function test_get_mentors_by_student_id()
    {
        $this->mockUser->shouldReceive('find')->with(8)->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('Mentors')->once()->andReturn([6]);
        $actual = $this->repository->getMentorsByStudentId(8);
        $this->assertEquals(1, count($actual));

        $this->mockUser->shouldReceive('find')->with(1)->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('Mentors')->once()->andReturn([]);
        $actual = $this->repository->getMentorsByStudentId(1);
        $this->assertEquals(0, count($actual));

        $this->mockUser->shouldReceive('find')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('Mentors')->once()->andReturnNull();
        $actual = $this->repository->getMentorsByStudentId(null);
        $this->assertNull($actual);
    }

    public function test_get_supervisors_by_student_id()
    {
        $this->mockUser->shouldReceive('find')->with(8)->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('Supervisors')->once()->andReturn([7]);
        $actual = $this->repository->getSupervisorsByStudentId(8);
        $this->assertEquals(1, count($actual));

        $this->mockUser->shouldReceive('find')->with(1)->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('Supervisors')->once()->andReturn([]);
        $actual = $this->repository->getSupervisorsByStudentId(1);
        $this->assertEquals(0, count($actual));

        $this->mockUser->shouldReceive('find')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('Supervisors')->once()->andReturnNull();
        $actual = $this->repository->getSupervisorsByStudentId(null);
        $this->assertNull($actual);
    }

    public function test_set_user_of_student()
    {
        $this->mockUser->shouldReceive('find')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('Users->sync')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('getAttribute')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('save')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('with->where')->once()->andReturn($this->mockUser);
        $actual = $this->repository->setUserOfStudent(12, 8);
        $this->assertInstanceOf('App\Models\User', $actual);
    }

    public function test_delete_user_of_student()
    {
        $this->mockUser->shouldReceive('find')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('Users->detach')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('getAttribute')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('save')->once()->andReturn($this->mockUser);
        $this->mockUser->shouldReceive('with->where')->once()->andReturn($this->mockUser);
        $actual = $this->repository->deleteUserOfStudent(12, 8);
        $this->assertInstanceOf('App\Models\User', $actual);
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
        $this->mockUser->shouldReceive('update')->andReturn($this->mockUser);
        $this->mock_set_detail();
        $actual = $this->repository->update($this->mockStudentData, 8);
        $this->assertInstanceOf('App\Models\User', $actual);

        $actual = $this->repository->update($this->mockStudentData, 'eight');
        $this->assertFalse($actual);
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
        $this->mockProjectService->shouldReceive('getMyProjectsByUserID')->with(1)->andReturn([
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
        $mockLog = new \App\Models\TaskLog();
        $mockLog->start = '2017-04-13';
        $mockLog->end = '2017-04-19';
        $actual = $this->repository->getReportsByUserId(1, array($mockLog, $mockLog));
        $this->assertCount(2, $actual);
        $this->assertArrayHasKey('reports' ,$actual[0]);
        $this->assertArrayHasKey('reports' ,$actual[1]);
    }

    public function test_get_statistic_by_users()
    {
        $this->mockTaskService->shouldReceive('whereHas->orderBy->get')->once()->andReturn([1, 2, 3, 4, 5, 6, 7, 8, 9]);
        $this->mockProjectService->shouldReceive('whereHas->orderBy->get')->once()->andReturn([2, 3, 4]);
        $user = new \App\Models\User();
        $user->id = 8;

        $user2 = new \App\Models\User();
        $user2->id = 10;
        $actual = $this->repository->getStatisticByUsers([
            $user, $user2
        ]);
        $this->assertCount(2, $actual);
        $this->assertArrayHasKey('total_projects' ,$actual);
        $this->assertArrayHasKey('total_tasks' ,$actual);
        $this->assertEquals(3, $actual['total_projects']);
        $this->assertEquals(9, $actual['total_tasks']);
    }
}
