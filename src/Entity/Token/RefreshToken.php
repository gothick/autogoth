<?php

namespace App\Entity\Token;

readonly final class RefreshToken extends AbstractApiToken
{
    public function __construct(
        string $accessToken,
        ?int $expires = null
    ) {
        parent::__construct($accessToken, $expires);
    }
}
