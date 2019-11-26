<?php

namespace TestMonitor\Mantis\Tests;

use Mockery;
use TestMonitor\Mantis\Client;
use PHPUnit\Framework\TestCase;
use TestMonitor\Mantis\Resources\Issue;
use TestMonitor\Mantis\Exceptions\NotFoundException;
use TestMonitor\Mantis\Exceptions\ValidationException;
use TestMonitor\Mantis\Exceptions\FailedActionException;
use TestMonitor\Mantis\Exceptions\UnauthorizedException;

class IssuesTest extends TestCase
{
    protected $project;

    protected $issue;

    protected function setUp(): void
    {
        parent::setUp();

        $this->project = ['id' => 1, 'name' => 'Project'];

        $this->issue = [
            'id' => '1',
            'summary' => 'Summary',
            'description' => 'Description',
            'category' => ['id' => '1', 'name' => 'Category'],
            'project' => $this->project,
        ];
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_should_return_a_list_of_issues()
    {
        // Given
        $mantis = new Client('url', 'token');

        $mantis->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode(['issues' => [$this->issue]]));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $issues = $mantis->issues($this->project['id']);

        // Then
        $this->assertIsArray($issues);
        $this->assertCount(1, $issues);
        $this->assertInstanceOf(Issue::class, $issues[0]);
        $this->assertEquals($this->issue['id'], $issues[0]->id);
        $this->assertEquals($this->issue['category']['name'], $issues[0]->category);
    }

    /** @test */
    public function it_should_throw_an_failed_action_exception_when_client_receives_bad_request_while_getting_a_list_of_issues()
    {
        // Given
        $mantis = new Client('url', 'token');

        $mantis->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(400);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(FailedActionException::class);

        // When
        $mantis->issues($this->project['id']);
    }

    /** @test */
    public function it_should_throw_a_notfound_exception_when_client_receives_not_found_while_getting_a_list_of_issues()
    {
        // Given
        $mantis = new Client('url', 'token');

        $mantis->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(404);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(NotFoundException::class);

        // When
        $mantis->issues($this->project['id']);
    }

    /** @test */
    public function it_should_throw_a_unauthorized_exception_when_client_lacks_authorization_for_getting_a_list_of_issues()
    {
        // Given
        $mantis = new Client('url', 'token');

        $mantis->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(401);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(UnauthorizedException::class);

        // When
        $mantis->issues($this->project['id']);
    }

    /** @test */
    public function it_should_throw_a_validation_exception_when_client_provides_invalid_data_while_a_getting_list_of_issues()
    {
        // Given
        $mantis = new Client('url', 'token');

        $mantis->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(json_encode(['message' => 'invalid']));

        $this->expectException(ValidationException::class);

        // When
        $mantis->issues($this->project['id']);
    }

    /** @test */
    public function it_should_return_a_single_issue()
    {
        // Given
        $mantis = new Client('url', 'token');

        $mantis->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode(['issues' => [$this->issue]]));

        // When
        $issue = $mantis->issue($this->issue['id']);

        // Then
        $this->assertInstanceOf(Issue::class, $issue);
        $this->assertEquals($this->issue['id'], $issue->id);
        $this->assertEquals($this->issue['category']['name'], $issue->category);
    }
}
