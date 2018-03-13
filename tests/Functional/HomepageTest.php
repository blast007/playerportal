<?php declare(strict_types=1);

namespace Tests\Functional;

class HomepageTest extends BaseTestCase
{
    /**
     * Test that the index route returns a rendered response containing the text 'adipiscing'
     */
    public function testGetHomepage()
    {
        $response = $this->runApp('GET', '/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('adipiscing', (string)$response->getBody());
    }

    /**
     * Test that the organizations page cannot be access without being authenticated
     */
    public function testGetOrganizations()
    {
        $response = $this->runApp('GET', '/organizations');

        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * Test that the index route won't accept a post request
     */
    public function testPostHomepageNotAllowed()
    {
        $response = $this->runApp('POST', '/', ['test']);

        $this->assertEquals(405, $response->getStatusCode());
        $this->assertContains('Method not allowed', (string)$response->getBody());
    }
}