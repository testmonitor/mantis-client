<?php

namespace TestMonitor\Jira\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;

class AttachmentsTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_should_add_an_attachment_to_an_issue()
    {
        // Given
        $mantis = new \TestMonitor\Mantis\Client('url', 'token');

        $mantis->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(''));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $result = $mantis->addAttachment(__DIR__ . '/files/logo.png', 1);

        // Then
        $this->assertEquals($result, '');
    }
}
