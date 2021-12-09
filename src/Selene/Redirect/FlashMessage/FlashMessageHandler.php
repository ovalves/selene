<?php
/**
 * @copyright   2021 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2021-12-09
 */

namespace Selene\Redirect\FlashMessage;

use Selene\Session\Session;

final class FlashMessageHandler
{
    protected const FLASH_KEYS = '61b23cf1d31372.37961493';
    protected const FLASH_HEADER = '61b23d0ace20f4.98927377';
    private ?array $messageKeys = [];
    private ?array $flashHeaders = [];
    private bool $stateKeep = false;

    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;

        register_shutdown_function([$this, 'stateKeeper']);

        $this->messageKeys =& $this->session->get(self::FLASH_KEYS);
        $this->flashHeaders =& $this->session->get(self::FLASH_HEADER);

        if (isset($this->flashHeaders)) {
            foreach ($this->flashHeaders as $header) {
                @header($header);
            }
        }

        $this->flashHeaders = [];

        if (!isset($this->messageKeys)) {
            $this->messageKeys = [];
        }

        $this->stateKeep = false;
    }

    public function keepState(bool $keep): void
    {
        $this->stateKeep = $keep;
    }

    public function setFlashHeader(mixed $header): void
    {
        $this->flashHeaders[] = $header;
    }

    public function removeFlashHeader(mixed $key): void
    {
        unset($this->flashHeaders[array_search($key, $this->flashHeaders)]);
    }

    public function setSessionData(mixed $key, mixed $value): void
    {
        $this->session->set($key, $value);
        $this->messageKeys[] = $key;
    }

    public function getSessionData(mixed $key): ?string
    {
        if (in_array($key, $this->getFlashKeys())) {
            return $this->session->get($key);
        }
        return null;
    }

    public function removeSessionData(mixed $key): void
    {
        $this->session->unset($key);
        unset($this->messageKeys[array_search($key, $this->messageKeys)]);
        if (empty($this->messageKeys)) {
            $this->stateKeep = false;
        }
    }

    public function getFlashKeys(): array
    {
        return $this->messageKeys;
    }

    public function stateKeeper(): void
    {
        if (false == $this->stateKeep) {
            foreach ($this->messageKeys as $messageKey) {
                $this->session->unset($messageKey);
            }
            $this->messageKeys = [];
        }
    }
}
