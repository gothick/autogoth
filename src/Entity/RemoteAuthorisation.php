<?php

namespace App\Entity;

use App\Entity\Token\AccessToken;
use App\Entity\Token\RefreshToken;
use App\Repository\RemoteAuthorisationRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: RemoteAuthorisationRepository::class)]
class RemoteAuthorisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $remoteServiceAlias = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $accessToken = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $refreshToken = null;

    #[ORM\Column(nullable: true)]
    private ?int $accessTokenExpires = null;

    #[ORM\Column(nullable: true)]
    private ?int $refreshTokenExpires = null;

    #[ORM\ManyToOne(inversedBy: 'remoteAuthorisations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRemoteServiceAlias(): ?string
    {
        return $this->remoteServiceAlias;
    }

    public function setRemoteServiceAlias(string $remoteServiceAlias): static
    {
        $this->remoteServiceAlias = $remoteServiceAlias;

        return $this;
    }

    public function getAccessToken(): ?AccessToken
    {
        if ($this->accessToken === null) {
            return null;
        }
        return new AccessToken(
            $this->accessToken,
            $this->accessTokenExpires
        );
    }

    public function setAccessToken(?AccessToken $accessToken): static
    {
        if ($accessToken === null) {
            $this->accessToken = null;
            $this->accessTokenExpires = null;
        } else {
            $this->accessToken = $accessToken->getToken();
            $this->accessTokenExpires = $accessToken->getExpires();
        }
        return $this;
    }

    public function getRefreshToken(): ?RefreshToken
    {
        if ($this->refreshToken === null) {
            return null;
        }
        return new RefreshToken(
            $this->refreshToken,
            $this->refreshTokenExpires
        );
    }

    public function setRefreshToken(?RefreshToken $refreshToken): static
    {
        if ($refreshToken === null) {
            $this->refreshToken = null;
            $this->refreshTokenExpires = null;
        } else {
            $this->refreshToken = $refreshToken->getToken();
            $this->refreshTokenExpires = $refreshToken->getExpires();
        }
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
