<?php


namespace App\Services;

use \Lcobucci\JWT\Configuration;

class TokenService
{
    private $config;
    private $now;

    /**
     * TokenService constructor.
     */
    public function __construct()
    {
        $this->config = Configuration::forUnsecuredSigner();
        $this->now = new \DateTimeImmutable();
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        //!!!  This way of token generation is NOT TO BE USED IN PRODUCTION
        //      Used it here only for example purpose
        $token = $this->config->builder()
            ->issuedAt($this->now)
            ->expiresAt($this->now->modify('+1 hour'))
            ->getToken($this->config->signer(), $this->config->signingKey());

        return $token->toString();
    }
}
