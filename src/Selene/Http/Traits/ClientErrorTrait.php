<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-23
 */

namespace Selene\Http\Traits;

use Exception;

trait ClientErrorTrait
{
    private int $errorCode;
    private bool $hasError = false;
    private string $errorMessage;

    public function hasError(): bool
    {
        return true == $this->hasError;
    }

    public function getErrorMessage(): string
    {
        return (true == $this->hasError) ? $this->errorMessage : '';
    }

    public function getErrorCode(): string
    {
        return (true == $this->hasError) ? $this->errorCode : 500;
    }

    public function applyClientError(Exception $exception)
    {
        $this->hasError = true;
        $this->errorMessage = $exception->getMessage();
        $this->errorCode = $exception->getCode();
    }
}
