<?php

class AppActionTest extends LocalWebTestCase
{
    public function testLogon()
    {
        $this->client->get('/');

        $this->assertEquals(200, $this->client->response->getStatusCode());
    }
    public function testLogonArea1B()
    {
        $parameters = array(
            'event' =>  'November 19-20, 2016:U16/U19 Playoffs',
            'user' => 'Area 1B',
            'passwd' => 'Touchline?',
            'Submit' => 'Logon',
        );
        $this->client->post('/', $parameters);
        $this->assertRedirectTo('/greet');
        $this->assertEquals(200, $this->client->response->getStatusCode());
        $this->testAllRoutes();
    }
    public function testLogonSection1()
    {
        $parameters = array(
            'event' =>  'November 19-20, 2016:U16/U19 Playoffs',
            'user' => 'Section 1',
            'passwd' => 'Center_ref!',
            'Submit' => 'Logon',
        );
        $this->client->post('/', $parameters);
        $this->assertRedirectTo('/greet');

        $this->assertEquals(200, $this->client->response->getStatusCode());

        $this->testAllRoutes();
    }
    public function testAllRoutes()
    {
        $this->client->get('/');
        $this->assertEquals(200, $this->client->response->getStatusCode());

        $this->client->get('/logon');
        $this->assertEquals(200, $this->client->response->getStatusCode());

        $this->client->get('/editref');
        $this->assertEquals(302, $this->client->response->getStatusCode());

        $this->client->get('/full');
        $this->assertEquals(302, $this->client->response->getStatusCode());

        $this->client->get('/greet');
        $this->assertEquals(302, $this->client->response->getStatusCode());

        $this->client->get('/lock');
        $this->assertEquals(302, $this->client->response->getStatusCode());

        $this->client->get('/refs');
        $this->assertEquals(302, $this->client->response->getStatusCode());

        $this->client->get('/master');
        $this->assertEquals(302, $this->client->response->getStatusCode());

        $this->client->get('/sched');
        $this->assertEquals(200, $this->client->response->getStatusCode());

        $this->client->get('/unlock');
        $this->assertEquals(302, $this->client->response->getStatusCode());

        $this->client->get('/fullexport');
        $this->assertEquals(200, $this->client->response->getStatusCode());

        $this->client->get('/adm');
        $this->assertEquals(200, $this->client->response->getStatusCode());

        $this->client->get('/adm/template');
        $this->assertEquals(200, $this->client->response->getStatusCode());

        $this->client->get('/adm/import');
        $this->assertEquals(200, $this->client->response->getStatusCode());

        $this->client->get('/log');
        $this->assertEquals(200, $this->client->response->getStatusCode());

        $this->client->get('/end');
        $this->assertEquals(302, $this->client->response->getStatusCode());
    }
}