<?php

namespace TestMonitor\Mantis\Tests;

use Mockery;
use TestMonitor\Mantis\Client;
use PHPUnit\Framework\TestCase;
use TestMonitor\Mantis\Resources\Project;
use TestMonitor\Mantis\Exceptions\Exception;
use TestMonitor\Mantis\Exceptions\NotFoundException;
use TestMonitor\Mantis\Exceptions\ValidationException;
use TestMonitor\Mantis\Exceptions\FailedActionException;
use TestMonitor\Mantis\Exceptions\UnauthorizedException;

class ProjectsTest extends TestCase
{
    protected $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->project = [
            'id' => '1',
            'name' => 'Name',
            'categories' => [['id' => '1', 'name' => 'Category']],
        ];
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_should_return_a_list_of_projects()
    {
        // Given
        $mantis = new Client('url', 'token');

        $mantis->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode(['projects' => [$this->project]]));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $projects = $mantis->projects();

        // Then
        $this->assertIsArray($projects);
        $this->assertCount(1, $projects);
        $this->assertInstanceOf(Project::class, $projects[0]);
        $this->assertEquals($this->project['id'], $projects[0]->id);
        $this->assertEquals($this->project['categories'], $projects[0]->categories);
        $this->assertIsArray($projects[0]->toArray());
    }

    /** @test */
    public function it_should_throw_an_failed_action_exception_when_client_receives_bad_request_while_getting_a_list_of_projects()
    {
        // Given
        $mantis = new Client('url', 'token');

        $mantis->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(400);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(FailedActionException::class);

        // When
        $mantis->projects();
    }

    /** @test */
    public function it_should_throw_a_notfound_exception_when_client_receives_not_found_while_getting_a_list_of_projects()
    {
        // Given
        $mantis = new Client('url', 'token');

        $mantis->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(404);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(NotFoundException::class);

        // When
        $mantis->projects();
    }

    /** @test */
    public function it_should_throw_a_unauthorized_exception_when_client_lacks_authorization_for_getting_a_list_of_projects()
    {
        // Given
        $mantis = new Client('url', 'token');

        $mantis->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(401);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(UnauthorizedException::class);

        // When
        $mantis->projects();
    }

    /** @test */
    public function it_should_throw_a_validation_exception_when_client_provides_invalid_data_while_a_getting_list_of_projects()
    {
        // Given
        $mantis = new Client('url', 'token');

        $mantis->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(json_encode(['message' => 'invalid']));

        $this->expectException(ValidationException::class);

        // When
        $mantis->projects();
    }

    /** @test */
    public function it_should_return_an_error_message_when_client_provides_invalid_data_while_a_getting_list_of_projects()
    {
        // Given
        $mantis = new Client('url', 'token');

        $mantis->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(json_encode(['errors' => ['invalid']]));

        // When
        try {
            $mantis->projects();
        } catch (ValidationException $exception) {
            // Then
            $this->assertIsArray($exception->errors());
            $this->assertEquals('invalid', $exception->errors()['errors'][0]);
        }
    }

    /** @test */
    public function it_should_throw_a_generic_exception_when_client_suddenly_becomes_a_teapot_while_a_getting_list_of_projects()
    {
        // Given
        $mantis = new Client('url', 'token');

        $mantis->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(418);
        $response->shouldReceive('getBody')->andReturn(json_encode(['rooibos' => 'anyone?']));

        $this->expectException(Exception::class);

        // When
        $mantis->projects();
    }

    /** @test */
    public function it_should_return_a_single_project()
    {
        // Given
        $mantis = new Client('url', 'token');

        $mantis->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode(['projects' => [$this->project]]));

        // When
        $project = $mantis->project(1);

        // Then
        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals($this->project['id'], $project->id);
        $this->assertEquals($this->project['categories'], $project->categories);
    }
}
