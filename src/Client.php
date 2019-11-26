<?php

namespace TestMonitor\Mantis;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;
use TestMonitor\Mantis\Exceptions\Exception;
use TestMonitor\Mantis\Exceptions\NotFoundException;
use TestMonitor\Mantis\Exceptions\ValidationException;
use TestMonitor\Mantis\Exceptions\FailedActionException;
use TestMonitor\Mantis\Exceptions\UnauthorizedException;

class Client
{
    use Actions\ManagesAttachments,
        Actions\ManagesIssues,
        Actions\ManagesProjects;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Create a new client instance.
     *
     * @param string $url
     * @param string $token
     */
    public function __construct(string $url, string $token)
    {
        $this->url = $url;
        $this->token = $token;
    }

    /**
     * Returns an Mantis client instance.
     *
     * @return \GuzzleHttp\Client
     */
    protected function client()
    {
        return $this->client ?? new GuzzleClient([
                'base_uri' => "{$this->url}/api/rest/",
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $this->token,
                    'Content-Type' => 'application/json',
                ],
            ]);
    }

    /**
     * @param \GuzzleHttp\Client $client
     */
    public function setClient(GuzzleClient $client)
    {
        $this->client = $client;
    }

    /**
     * Make a GET request to Mantis servers and return the response.
     *
     * @param string $uri
     *
     * @param array $payload
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\Mantis\Exceptions\FailedActionException
     * @throws \TestMonitor\Mantis\Exceptions\NotFoundException
     * @throws \TestMonitor\Mantis\Exceptions\ValidationException
     * @return mixed
     */
    protected function get($uri, array $payload = [])
    {
        return $this->request('GET', $uri, $payload);
    }

    /**
     * Make a POST request to Mantis servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\Mantis\Exceptions\FailedActionException
     * @throws \TestMonitor\Mantis\Exceptions\NotFoundException
     * @throws \TestMonitor\Mantis\Exceptions\ValidationException
     * @return mixed
     */
    protected function post($uri, array $payload = [])
    {
        return $this->request('POST', $uri, $payload);
    }

    /**
     * Make a PUT request to Mantis servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\Mantis\Exceptions\FailedActionException
     * @throws \TestMonitor\Mantis\Exceptions\NotFoundException
     * @throws \TestMonitor\Mantis\Exceptions\ValidationException
     * @return mixed
     */
    protected function patch($uri, array $payload = [])
    {
        return $this->request('PATCH', $uri, $payload);
    }

    /**
     * Make a DELETE request to Mantis servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\Mantis\Exceptions\FailedActionException
     * @throws \TestMonitor\Mantis\Exceptions\NotFoundException
     * @throws \TestMonitor\Mantis\Exceptions\ValidationException
     * @return mixed
     */
    protected function delete($uri, array $payload = [])
    {
        return $this->request('DELETE', $uri, $payload);
    }

    /**
     * Make request to Mantis servers and return the response.
     *
     * @param string $verb
     * @param string $uri
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\Mantis\Exceptions\FailedActionException
     * @throws \TestMonitor\Mantis\Exceptions\NotFoundException
     * @throws \TestMonitor\Mantis\Exceptions\ValidationException
     * @return mixed
     */
    protected function request($verb, $uri, array $payload = [])
    {
        $response = $this->client()->request(
            $verb,
            $uri,
            $payload
        );

        if (! in_array($response->getStatusCode(), [200, 201, 204, 206])) {
            return $this->handleRequestError($response);
        }

        $responseBody = (string) $response->getBody();

        return json_decode($responseBody, true) ?: $responseBody;
    }

    /**
     * @param  \Psr\Http\Message\ResponseInterface $response
     *
     * @throws \TestMonitor\Mantis\Exceptions\ValidationException
     * @throws \TestMonitor\Mantis\Exceptions\NotFoundException
     * @throws \TestMonitor\Mantis\Exceptions\FailedActionException
     * @throws \Exception
     * @return void
     */
    protected function handleRequestError(ResponseInterface $response)
    {
        if ($response->getStatusCode() == 422) {
            throw new ValidationException(json_decode((string) $response->getBody(), true));
        }

        if ($response->getStatusCode() == 404) {
            throw new NotFoundException();
        }

        if ($response->getStatusCode() == 401 || $response->getStatusCode() == 403) {
            throw new UnauthorizedException();
        }

        if ($response->getStatusCode() == 400) {
            throw new FailedActionException((string) $response->getBody());
        }

        throw new Exception((string) $response->getStatusCode());
    }
}
