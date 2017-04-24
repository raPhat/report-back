<?php
//
//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;
//
//class UserTest extends TestCase
//{
//    use DatabaseTransactions;
//
//    public function testLogin()
//    {
//        // POST  api/auth
//        $response = $this->json('POST', '/api/auth', [
//            'email' => 'karjkeng@hotmail.com',
//            'password' => 'karjkeng'
//        ])
////            ->dump()
//            ->seeJsonStructure([
//                'token'
//            ])
//            ->decodeResponseJson();
//
//        return $response['token'];
//    }
//
//    public function testGetMe()
//    {
//        // GET  api/auth/me
//        $token = $this->testLogin();
//        $this->refreshApplication();
//        $server = [
//            'Authorization' => 'Bearer '.$token
//        ];
//        $this->get('/api/auth/me', $server)
//            ->seeJsonStructure([
//                'id',
//                'first_name',
//                'last_name',
//                'email',
//                'description',
//                'company',
//                'position',
//                'avatar',
//                'sign',
//                'start',
//                'role',
//                'code',
//            ]);
//
//        $this->refreshApplication();
//        $this->get('/api/auth/me')->assertResponseStatus(500);
//    }
//
//    public function testGetUserByCode() {
//        // GET  api/users/code/{id}
//        $token = $this->testLogin();
//        $this->refreshApplication();
//        $server = [
//            'Authorization' => 'Bearer '.$token
//        ];
//        $this->get('/api/users/code/11111', $server)
////            ->dump()
//            ->seeJson([
//                'id' => 4,
//                'first_name' => 'Eugene',
//                'last_name' => 'Gomez',
//                'email' => 'super@hotmail.com',
//                'description' => 'super supervisor',
//                'role' => 'supervisor',
//                'code' => '11111',
//            ]);
//    }
//
//    public function testSetUserOfStudent() {
//        // POST api/users/code
//        $token = $this->testLogin();
//        $this->refreshApplication();
//        $server = [
//            'Authorization' => 'Bearer '.$token
//        ];
//        $body = [
//            'user' => [
//                'id' => 4
//            ]
//        ];
//        $response = $this->post('/api/users/code', $body, $server)
////            ->dump();
//            ->decodeResponseJson();
//
//        $this->assertCount(1, $response['supervisors']);
//        $this->assertEquals($response['supervisors'][0]['id'], 4);
//    }
//
//    public function testGetMyReports() {
//        // GET api/users/reports
//        $token = $this->testLogin();
//        $this->refreshApplication();
//        $server = [
//            'Authorization' => 'Bearer '.$token
//        ];
//        $response = $this->get('/api/users/reports?dates=[{%22start%22:%2208/29/2016%22,%22end%22:%2209/04/2016%22}]', $server)
////            ->dump()
//            ->seeJsonStructure([
//                [
//                    'id',
//                    'name',
//                    'description',
//                    'start',
//                    'image_id',
//                    'user_id',
//                    'reports'
//                ]
//            ])
//            ->decodeResponseJson();
//
//        $this->assertCount(1, $response);
//        $this->assertCount(1, $response[0]['reports']);
//    }
//
//    public function testGetNotifiesByUserId() {
//        // GET api/auth/notifies
//        $token = $this->testLogin();
//        $this->refreshApplication();
//        $server = [
//            'Authorization' => 'Bearer '.$token
//        ];
//        $response = $this->get('/api/auth/notifies', $server)
////            ->dump()
//            ->decodeResponseJson();
//
//        $this->assertCount(0, $response);
//    }
//
//    public function testDeleteUserOfStudent() {
//        // DELETE api/users/code/{id}
//        $token = $this->testLogin();
//        $this->refreshApplication();
//        $server = [
//            'Authorization' => 'Bearer '.$token
//        ];
//        $response = $this->delete('/api/users/code/4', [], $server)
////            ->dump()
//            ->decodeResponseJson();
//
//        $this->assertCount(0, $response['supervisors']);
//    }
//
//    public function testGetStatisticByUserID() {
//        // GET api/users/statistic/{id}
//        $token = $this->testLogin();
//        $this->refreshApplication();
//        $server = [
//            'Authorization' => 'Bearer '.$token
//        ];
//        $response = $this->get('/api/users/statistic/1', $server)
////            ->dump()
//            ->seeJsonStructure([
//                'total_projects',
//                'total_tasks',
//                'total_days'
//            ])
//            ->decodeResponseJson();
//
//        $this->assertEquals(1, $response['total_projects']);
//        $this->assertEquals(0, $response['total_tasks']);
//        $this->assertEquals(0, $response['total_days']);
//
//    }
//
//    public function testShow() {
//        // GET api/users/{id}
//        $token = $this->testLogin();
//        $this->refreshApplication();
//        $server = [
//            'Authorization' => 'Bearer '.$token
//        ];
//        $response = $this->get('/api/users/1', $server)
////            ->dump()
//            ->seeJson([
//                'id' => 1,
//                'first_name' => 'Veerapat',
//                'last_name' => 'In-ongkarn',
//                'email' => 'karjkeng@hotmail.com',
//                'description' => 'Hi!....',
//                'company' => 'Buzzwoo!',
//                'position' => 'Junior Frontend Developer'
//            ])
//            ->decodeResponseJson();
//    }
//
//    public function testUpdate() {
//        // PUT api/users/{id}
//        $token = $this->testLogin();
//        $this->refreshApplication();
//        $server = [
//            'Authorization' => 'Bearer '.$token
//        ];
//        $body = [
//            'first_name' => 'Tony',
//            'last_name' => 'Stark',
//            'email' => 'karjkeng@hotmail.com',
//            'description' => 'I\'m iron man.' ,
//            'company' => 'STARK!',
//            'position' => 'CEO',
//        ];
//        $response = $this->put('/api/users/1', $body, $server)
////            ->dump()
//            ->seeJson([
//                'id' => 1,
//                'first_name' => 'Tony',
//                'last_name' => 'Stark',
//                'email' => 'karjkeng@hotmail.com',
//                'description' => 'I\'m iron man.' ,
//                'company' => 'STARK!',
//                'position' => 'CEO'
//            ])
//            ->decodeResponseJson();
//
//        $this->refreshApplication();
//        $body = [
//            'first_name' => 'Veerapat',
//            'last_name' => 'In-ongkarn',
//            'email' => 'karjkeng@hotmail.com',
//            'description' => 'Hi!....',
//            'company' => 'Buzzwoo!',
//            'position' => 'Junior Frontend Developer',
//        ];
//        $response = $this->put('/api/users/1', $body, $server)
////            ->dump()
//            ->seeJson([
//                'id' => 1,
//                'first_name' => 'Veerapat',
//                'last_name' => 'In-ongkarn',
//                'email' => 'karjkeng@hotmail.com',
//                'description' => 'Hi!....',
//                'company' => 'Buzzwoo!',
//                'position' => 'Junior Frontend Developer'
//            ])
//            ->decodeResponseJson();
//    }
//}
