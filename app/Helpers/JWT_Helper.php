<?php

use App\Models\API_User;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getJWTFromRequest($authenticationHeader): string
{
    if (is_null($authenticationHeader)) { //JWT is absent
        throw new Exception('Missing or invalid JWT in request');
    }
    //JWT is sent from client in the format Bearer XXXXXXXXX
    return explode(' ', $authenticationHeader)[1];
}

function validateJWTFromRequest(string $encodedToken)
{
    $key = Services::getSecretKey();
    $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));
    $userModel = new API_User();
    $userModel->findUser($decodedToken->username);
}

function getSignedJWTForUser(string $username)
{
    $issuedAtTime = time();
    $tokenTimeToLive = getenv('JWT_TIME_TO_LIVE');
    $tokenExpiration = $issuedAtTime + $tokenTimeToLive;
    $payload = [
        'username' => $username,
        'iat' => $issuedAtTime,
        'exp' => $tokenExpiration,
    ];

    $jwt = [
        JWT::encode($payload, Services::getSecretKey(), 'HS256'),
        $tokenExpiration
    ];
    return $jwt;
}

