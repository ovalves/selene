<?php
/**
 * @copyright   2021 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2021-12-09
 */

namespace Selene\Redirect;

use Selene\Session\Session;
use Psr\Container\ContainerInterface;
use Selene\Container\ServiceContainer;
use Selene\Redirect\FlashMessage\FlashMessageHandler;

class Redirect
{
    public static $flashMessageHandler = null;
    protected int $statusCode = 301;
    protected string $redirectBy = 'selene-redirector';
    protected string $redirectUrl;
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container, Session $session)
    {
        $this->container = $container;

        if (!isset(static::$flashMessageHandler)) {
            static::$flashMessageHandler = new FlashMessageHandler($session);
        }
    }

    public function message(mixed $key, string $message): self
    {
        self::$flashMessageHandler->setSessionData($key, $message);
        return $this;
    }

    public function to(string $url): self
    {
        $this->redirectUrl = $url;
        return $this;
    }

    public function status(int $status = 301): self
    {
        $this->statusCode = $status;
        return $this;
    }

    public function by(string $redirectBy = 'selene-redirector'): self
    {
        $this->redirectBy = $redirectBy;

        return $this;
    }

    public function go(): void
    {
        if (!isset($this->redirectUrl)) {
            $this->back();
            return;
        }

        self::$flashMessageHandler->keepState(true);
        @header("X-Redirect-By: {$this->redirectBy}", true, $this->statusCode);
        @header("Location: {$this->redirectUrl}", true, $this->statusCode);

        exit();
    }

    public function back(): void
    {
        $this->redirectUrl = $this
            ->container
            ->get(ServiceContainer::REQUEST)
            ->getRequestParams()['HTTP_REFERER'] ?? null;

        $this->go();
    }
}
