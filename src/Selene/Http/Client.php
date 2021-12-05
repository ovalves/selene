<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-23
 */

namespace Selene\Http;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Selene\Http\Traits\ClientErrorTrait;
use Psr\Http\Message\ResponseInterface;

final class Client
{
    use ClientErrorTrait;

    public const GET = 'GET';
    public const POST = 'POST';
    public const HEAD = 'HEAD';
    public const PUT = 'PUT';
    public const PATCH = 'PATCH';
    public const DELETE = 'DELETE';

    private array $headers = [];
    private string $baseUri;

    private ?GuzzleClient $client = null;
    private ?ResponseInterface $response = null;

    public function __construct(string $baseUri, array $headers = [])
    {
        $this->headers = $headers;
        $this->baseUri = $baseUri;
        $this->makeClient();
    }

    public function request(string $method, string $uri = '', array $options = []): self
    {
        try {
            $this->response = $this->client->request(
                $method,
                $uri,
                $options
            );

            return $this;
        } catch (Exception $e) {
            log_error($e);
            $this->applyClientError($e);

            return $this;
        }
    }

    public function asJson(): string
    {
        try {
            $this->shouldThrowException();

            return $this->response->getBody()->getContents();
        } catch (Exception $e) {
            log_error($e);

            return json_encode([
                'error' => true,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
        }
    }

    public function asArray(): array
    {
        try {
            $this->shouldThrowException();

            return json_decode($this->response->getBody()->getContents(), true);
        } catch (Exception $e) {
            log_error($e);

            return [
                'error' => true,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        }
    }

    public function getResponse(): array
    {
        try {
            $this->shouldThrowException();

            return [
                'metadata' => [
                    'status-code' => $this->response->getStatusCode(),
                    'reason-phrase' => $this->response->getReasonPhrase(),
                    'protocol-version' => $this->response->getProtocolVersion(),
                ],
                'headers' => $this->response->getHeaders(),
                'body' => $this->asArray(),
            ];
        } catch (Exception $e) {
            log_error($e);

            return [
                'error' => true,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        }
    }

    public function getResponseObject(): ResponseInterface | array
    {
        try {
            $this->shouldThrowException();

            return $this->response;
        } catch (Exception $e) {
            log_error($e);

            return [
                'error' => true,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        }
    }

    private function shouldThrowException(): void
    {
        if ($this->hasError()) {
            throw new Exception($this->getErrorMessage(), $this->getErrorCode());
        }

        if (empty($this->response)) {
            throw new Exception('Erro ao realizar a requisição solicitada', 500);
        }
    }

    private function makeClient(): GuzzleClient
    {
        if ($this->client instanceof GuzzleClient) {
            return $this->client;
        }

        $this->client = new GuzzleClient(array_merge([], ['base_uri' => $this->baseUri, 'headers' => $this->headers]));

        return $this->client;
    }
}
