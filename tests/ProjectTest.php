<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;

    public function testLogin()
    {
        // POST  api/auth
        $response = $this->json('POST', '/api/auth', [
            'email' => 'karjkeng@hotmail.com',
            'password' => 'karjkeng'
        ])
//            ->dump()
            ->seeJsonStructure([
                'token'
            ])
            ->decodeResponseJson();

        return $response['token'];
    }

    public function testGetMyProject() {
        // GET  api/projects/myProject
        $token = $this->testLogin();
        $this->refreshApplication();
        $server = [
            'Authorization' => 'Bearer '.$token
        ];
        $response = $this->get('/api/projects/myProject', $server)
//            ->dump()
            ->decodeResponseJson();

        $this->assertCount(1, $response);
        $this->assertEquals('1st Project', $response[0]['name']);
    }

    public function testGetProjectsByUserID() {
        // GET  api/projects/user/{id}
        $token = $this->testLogin();
        $this->refreshApplication();
        $server = [
            'Authorization' => 'Bearer '.$token
        ];
        $response = $this->get('/api/projects/user/1', $server)
//            ->dump()
            ->decodeResponseJson();

        $this->assertCount(1, $response);
        $this->assertEquals('1st Project', $response[0]['name']);
    }

    public function testGetLogs() {
        // GET  api/projects/logs/{id}
        $token = $this->testLogin();
        $this->refreshApplication();
        $server = [
            'Authorization' => 'Bearer '.$token
        ];
        $response = $this->get('/api/projects/logs/1', $server)
//            ->dump()
            ->decodeResponseJson();

        $this->assertCount(0, $response);
    }

    public function testIndex() {
        // GET  api/projects/{id}
        $token = $this->testLogin();
        $this->refreshApplication();
        $server = [
            'Authorization' => 'Bearer '.$token
        ];
        $response = $this->get('/api/projects/1', $server)
//            ->dump()
            ->seeJson([
                'id' => 1,
                'name' => '1st Project',
                'description' => 'Yeahhh!'
            ])
            ->decodeResponseJson();
    }

    public function testStore() {
        // POST  api/projects
        $token = $this->testLogin();
        $this->refreshApplication();
        $server = [
            'Authorization' => 'Bearer '.$token
        ];
        $body = [
            'name' => 'Test Project',
            'description' => 'test description',
            'start' => '2017-04-13'
        ];
        $response = $this->post('/api/projects', $body, $server)
//            ->dump()
            ->seeJson([
                'name' => 'Test Project',
                'description' => 'test description',
                'start' => '2017-04-13'
            ])
            ->decodeResponseJson();
    }

    public function testUpdate() {
        // PUT  api/projects
        $token = $this->testLogin();
        $this->refreshApplication();
        $server = [
            'Authorization' => 'Bearer '.$token
        ];
        $body = [
            'name' => 'Update Test Project',
            'description' => 'Update test description',
            'start' => '2017-04-13'
        ];
        $response = $this->put('/api/projects/2', $body, $server)
//            ->dump()
            ->seeJson([
                'name' => 'Update Test Project',
                'description' => 'Update test description'
            ])
            ->decodeResponseJson();
    }

    public function testDelete() {
        // DELETE  api/projects
        $token = $this->testLogin();
        $this->refreshApplication();
        $server = [
            'Authorization' => 'Bearer '.$token
        ];
        $response = $this->delete('/api/projects/2', $server)
//            ->dump()
            ->seeJson([
                'name' => 'Update Test Project',
                'description' => 'Update test description'
            ])
            ->decodeResponseJson();
    }
}
