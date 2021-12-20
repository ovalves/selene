<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Selene\Response;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Classe que lida com Server Requests.
 */
class Response extends ResponseAbstract
{
    /**
     * Guarda os dados do header da response.
     *
     * @var string
     */
    protected $headerName;

    /**
     * Guarda os dados do status da response.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Guarda os dados do mensagem da response.
     *
     * @var string
     */
    protected $reasonPhrase;

    /**
     * Guarda os dados do protocolo da response.
     *
     * @var string
     */
    protected $protocolVersion;

    /**
     * Guarda os dados da página de redirecionamento da response.
     *
     * @var string
     */
    protected $redirectToPage;

    /**
     * Guarda os dados da página de redirecionamento da response.
     *
     * @var string
     */
    protected $unauthorized = false;

    /**
     * Seta a página de redirecionamento.
     *
     * @param string $page
     */
    public function withRedirectTo($page): self
    {
        $this->redirectToPage = $page;

        return $this;
    }

    /**
     * Seta a página de redirecionamento.
     */
    public function setUnauthorized(): self
    {
        $this->unauthorized = true;

        return $this;
    }

    /**
     * Seta a página de redirecionamento.
     */
    public function isUnauthorized(): bool
    {
        return (bool) $this->unauthorized ?? false;
    }

    /**
     * Faz o redirecionamento.
     */
    public function redirectToLoginPage(): void
    {
        header("Location: {$this->redirectToPage}");
        exit;
    }

    /**
     * Return a new JSON response from the application.
     *
     * @param mixed $data
     * @param int   $status
     * @param int   $options
     */
    public function json($data = [], $status = 200, array $headers = [], $options = 0): JsonResponse
    {
        return (new JsonResponse($data, $status, $headers, $options))->send();
    }

    public function success(mixed $data = [], mixed $page = [], int $httpCode = parent::HTTP_OK, string $message = null, array $headers = [], int $options = 0): JsonResponse
    {
        return $this->json(
            [
                'httpCode' => $httpCode,
                'httpMessage' => $message,
                'data' => $data,
                'page' => $page,
                'transaction' => [
                    'localTransactionId' => (string) uniqid('', true),
                    'localTransactionDate' => date(\DateTime::ATOM, strtotime('now')),
                ],
                'status' => true,
            ],
            $httpCode,
            $headers,
            $options
        );
    }

    public function error(Exception $exception, array $headers = [], int $options = 0): JsonResponse
    {
        return $this->json(
            [
                'httpCode' => $exception->getCode(),
                'httpMessage' => $exception->getMessage(),
                'data' => [],
                'page' => [],
                'transaction' => [
                    'localTransactionId' => (string) uniqid('', true),
                    'localTransactionDate' => date(\DateTime::ATOM, strtotime('now')),
                ],
                'status' => false,
            ],
            $exception->getCode(),
            $headers,
            $options
        );
    }
}
