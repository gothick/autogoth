<?php

namespace App\Entity\Token;

readonly class AbstractApiToken
{
    public function __construct(
        public string $token,
        public ?int $expires = null,
    ) {
    }
    public function isExpired(): bool
    {
        if ($this->expires === null) {
            return false; // Token does not expire
        }
        return time() >= $this->expires;
    }
    public function getToken(): string
    {
        return $this->token;
    }
    public function getExpires(): ?int
    {
        return $this->expires;
    }
}
