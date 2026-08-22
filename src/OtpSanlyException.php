<?php

declare(strict_types=1);

namespace OtpSanly;

class OtpSanlyException extends \RuntimeException
{
    /** @var array<string, mixed> */
    private array $body;

    /**
     * @param array<string, mixed> $body
     */
    public function __construct(string $message, int $status, array $body = [])
    {
        parent::__construct($message, $status);
        $this->body = $body;
    }

    /** @return array<string, mixed> */
    public function getBody(): array
    {
        return $this->body;
    }
}
