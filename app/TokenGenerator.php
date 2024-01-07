<?php

namespace App;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class TokenGenerator
{
    public static function generate(): string
    {
        $signer = new Sha256();
        $issuedAt = new \DateTimeImmutable();
        $expiresAt = $issuedAt->modify('+1 hour');

        $token = (new Builder())
            ->issuedBy('your_issuer')
            ->permittedFor('your_audience')
            ->issuedAt($issuedAt)
            ->expiresAt($expiresAt)
            ->sign($signer, 'your_secret_key')
            ->getToken();

        return (string) $token;
    }
}
