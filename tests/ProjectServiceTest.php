<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Database\Eloquent\Collection;

class ProjectServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->mock = $this->mock('App\Models\Project');
        $this->mockLog = $this->mock('App\Models\TaskLog');

        $this->repository = new \App\Services\ProjectService($this->mock, $this->mockLog);
    }

    public function mock($class)
    {
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);
        return $mock;
    }

    public function test_all()
    {
        $this->mock->shouldReceive('get')->once()->andReturn(new Collection());
        $actual = $this->repository->all();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

    public function test_show()
    {
        $this->mockLog->shouldReceive('with->whereHas->orderBy->get')->andReturn(new Collection());

        $this->mock->shouldReceive('where')->with('id', 1)->once()->andReturn($this->mock);
        $this->mock->shouldReceive('first')->once()->andReturn($this->mock);
        $this->mock->shouldReceive('offsetSet')->once()->andReturn($this->mock);
        $actual = $this->repository->show(1);
        $this->assertInstanceOf('App\Models\Project', $actual);

        $this->mock->shouldReceive('where')->with('id', 99)->once()->andReturn($this->mock);
        $this->mock->shouldReceive('first')->once()->andReturnNull();
        $actual = $this->repository->show(99);
        $this->assertNull($actual);

        $this->mock->shouldReceive('where')->with('id', 'string')->once()->andReturn($this->mock);
        $this->mock->shouldReceive('first')->once()->andReturnNull();
        $actual = $this->repository->show('string');
        $this->assertNull($actual);
    }

    public function test_store()
    {
        $request = new \Illuminate\Support\Facades\Request();
        $request->name = '';
        $request->description = '';
        $actual = $this->repository->store($request, 1);
        $this->assertInstanceOf('App\Models\Project', $actual);

        $request = new \Illuminate\Support\Facades\Request();
        $request->name = '';
        $actual = $this->repository->store($request, 1);
        $this->assertFalse($actual);

        $request = new \Illuminate\Support\Facades\Request();
        $request->description = '';
        $actual = $this->repository->store($request, 1);
        $this->assertFalse($actual);

        $request = new \Illuminate\Support\Facades\Request();
        $actual = $this->repository->store($request, 1);
        $this->assertFalse($actual);
    }

    public function test_update()
    {
        $this->mock->shouldReceive('findOrFail')->once()->andReturn($this->mock);
        $this->mock->shouldReceive('setAttribute')->andReturn($this->mock);
        $this->mock->shouldReceive('save')->once()->andReturn(new Collection());
        $request = new \Illuminate\Support\Facades\Request();
        $request->name = 'Name';
        $request->description = 'Description';
        $actual = $this->repository->update($request, 1);
        $this->assertInstanceOf('App\Models\Project', $actual);

        $request = new \Illuminate\Support\Facades\Request();
        $request->name = 'Name';
        $actual = $this->repository->update($request, 1);
        $this->assertFalse($actual);

        $request = new \Illuminate\Support\Facades\Request();
        $request->description = 'Description';
        $actual = $this->repository->update($request, 1);
        $this->assertFalse($actual);

        $request = new \Illuminate\Support\Facades\Request();
        $actual = $this->repository->update($request, 1);
        $this->assertFalse($actual);
    }

    public function test_destroy()
    {
        $this->mock->shouldReceive('destroy')->once()->andReturn($this->mock);
        $actual = $this->repository->destroy(1);
        $this->assertInstanceOf('App\Models\Project', $actual);

        $actual = $this->repository->destroy('string');
        $this->assertFalse($actual);

        $actual = $this->repository->destroy(null);
        $this->assertFalse($actual);
    }

    public function test_get_my_projects_by_user_id()
    {
        $this->mock->shouldReceive('with->where->get')->once()->andReturn(new Collection());
        $actual = $this->repository->getMyProjectsByUserID(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);

        $actual = $this->repository->getMyProjectsByUserID('string');
        $this->assertFalse($actual);

        $actual = $this->repository->getMyProjectsByUserID(null);
        $this->assertFalse($actual);
    }

    public function test_get_projects_by_user_id()
    {
        $this->mock->shouldReceive('with->where->get')->once()->andReturn(new Collection());
        $actual = $this->repository->getProjectsByUserID(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);

        $actual = $this->repository->getProjectsByUserID('string');
        $this->assertFalse($actual);

        $actual = $this->repository->getProjectsByUserID(null);
        $this->assertFalse($actual);
    }

    public function test_get_logs_by_project()
    {
        $this->mockLog->shouldReceive('with->whereHas->orderBy->get')->andReturn(new Collection());
        $actual = $this->repository->getLogsByProject(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

}
