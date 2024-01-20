<?php

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample(): void
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(),
            $this->response->getContent()
        );
    }

    public function testXDebugHash(): void
    {
        $this->get('/api/info');

        $this->seeHeader('X-DEBUG-HASH', sha1((string)$this->response->getContent()));
    }
}
