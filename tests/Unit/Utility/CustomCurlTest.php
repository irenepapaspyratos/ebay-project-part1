<?php

namespace Tests\Unit\Utility;

use App\Utility\CustomCurl;
use Codeception\Test\Unit;
use donatj\MockWebServer\MockWebServer;

/**
 * The 'CustomCurlTest' is a unit test class for testing the 'CustomCurl' class.
 * 
 * Tests the custom cURL functionality. As an external library is used, 
 * only the presence of a response or an exception will be checked.
 */
class CustomCurlTest extends Unit {

    protected $tester;
    private $server;
    private $apiUrl;
    private $customCurl;

    /**
     * Sets up the necessary environment for running tests 
     * by starting a mock web server.
     */
    protected function _before() {
        $this->server = new MockWebServer;
        $this->server->start();
    }

    /**
     * Stops the server after each test.
     */
    protected function _after() {
        $this->server->stop();
    }

    /**
     * Tests whether an exception is thrown with the correct error
     * message when the cURL request fails to resolve the host.
     */
    public function testExecuteCurlFailure() {

        // Arrange
        $this->apiUrl = '-.-';
        $this->customCurl = new CustomCurl($this->apiUrl);

        // Assert that an exception is thrown with corresponding error message reporting the non resolvable host
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('cURL ERROR: Could not resolve host: -.-');

        // Act
        $this->customCurl->executeCurl([], $this->apiUrl);
    }

    /**
     * Tests whether the response is a string with expected content by
     * setting up a mock response for a specific endpoint on a mock web server.
     */
    public function testExecuteCurlSuccess() {

        // Setting up a mock response for a specific endpoint on the mock web server.
        $this->apiUrl = $this->server->setResponseOfPath(
            '/test-endpoint',
            new \donatj\MockWebServer\Response('Response-String', [], 200)
        );

        // Arange
        $this->customCurl = new CustomCurl($this->apiUrl);

        // Act
        $response = $this->customCurl->executeCurl([], '');

        // Assert that the response of the 'executeCurl' method is a string with expected content
        $this->assertIsString($response);
        $this->assertEquals('Response-String', $response);
    }

    /**
     * Tests whether the response is a string with expected content by
     * setting up a mock response for a specific endpoint on a mock web server.
     */
    public function testExecuteCurlWithHeadersSuccess() {

        // Setting up a mock response for a specific endpoint on the mock web server with headers.
        $responseHeaders = [
            'X-Powered-By' => 'PHP/8.2.8',
            'Content-type' => 'text/html; charset=UTF-8',
        ];

        $this->apiUrl = $this->server->setResponseOfPath(
            '/test-endpoint',
            new \donatj\MockWebServer\Response('Response-String', $responseHeaders, 200)
        );

        // Arange
        $this->customCurl = new CustomCurl($this->apiUrl);

        // Act
        $response = $this->customCurl->executeCurl([], '', true);


        // Assert that the response of the 'executeCurl' method is a string with expected content
        $this->assertIsArray($response);

        $this->assertIsString($response['body']);
        $this->assertEquals('Response-String', $response['body']);

        $this->assertIsString($response['headers']);
        $this->assertStringContainsString('Content-type: text/html; charset=UTF-8', $response['headers']);
        // As Mock gives changing values, the following are just tested for existence
        $this->assertStringContainsString('Host:', $response['headers']);
        $this->assertStringContainsString('Date:', $response['headers']);
    }
}
