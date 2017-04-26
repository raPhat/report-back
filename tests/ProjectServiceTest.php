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

        $this->repository = new \App\Services\ProjectService($this->mock);
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
        $this->mock->shouldReceive('where->first')->once()->andReturn(new Collection());
        $actual = $this->repository->show(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual['logs']);
    }

    public function test_store()
    {
        $request = new \Illuminate\Support\Facades\Request();
        $request->name = '';
        $request->description = '';
        $actual = $this->repository->store($request, 1);
        $this->assertInstanceOf('App\Models\Project', $actual);
    }

    public function test_update()
    {
        $this->mock->shouldReceive('findOrFail')->once()->andReturn($this->mock);
        $this->mock->shouldReceive('setAttribute')->andReturn($this->mock);
        $this->mock->shouldReceive('save')->once()->andReturn(new Collection());
        $request = new \Illuminate\Support\Facades\Request();
        $request->name = '';
        $request->description = '';
        $actual = $this->repository->update($request, 1);
        $this->assertInstanceOf('App\Models\Project', $actual);
    }

    public function test_destroy()
    {
        $this->mock->shouldReceive('destroy')->once()->andReturn(new Collection());
        $actual = $this->repository->destroy(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

    public function test_get_my_projects_by_user_id()
    {
        $this->mock->shouldReceive('with->where->get')->once()->andReturn(new Collection());
        $actual = $this->repository->getMyProjectsByUserID(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

    public function test_get_projects_by_user_id()
    {
        $this->mock->shouldReceive('with->where->get')->once()->andReturn(new Collection());
        $actual = $this->repository->getProjectsByUserID(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

    public function test_get_logs_by_project()
    {
        $actual = $this->repository->getLogsByProject(1);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $actual);
    }

}
