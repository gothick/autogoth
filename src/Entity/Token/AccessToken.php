<?php

namespace App\Entity\Token;

readonly final class AccessToken extends AbstractApiToken
{
    public function __construct(
        string $accessToken,
        ?int $expires = null
    ) {
        parent::__construct($accessToken, $expires);
    }
}
