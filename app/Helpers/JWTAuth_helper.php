<?php

use Config\Services;
use Firebase\JWT\JWT;

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
    $decodedToken = JWT::decode($encodedToken, $key, ['HS256']);
    $email = ($decodedToken->data->email ?? "");
    if($email == ""){
        throw new Exception('User does not exist for specified username');
    }
    // $userModel = new UserModel();
    // $userModel->validateUsername($username);
}

function getSignedJWTForUser(string $email)
{
    $issuedAtTime = time();
    $tokenTimeToLive = getenv('JWT_TIME_TO_LIVE');
    $tokenExpiration = $issuedAtTime + $tokenTimeToLive;
    $payload = [
        'email' => $email,
        'iat' => $issuedAtTime,
        'exp' => $tokenExpiration,
    ];

    $jwt = JWT::encode($payload, Services::getSecretKey());
    return $jwt;
}

function getJWTData(string $encodedToken, $key = ""){
    if($key == "") $key = Services::getSecretKey();
    $decodedToken = JWT::decode($encodedToken, $key, ['HS256']);
    $email = ($decodedToken->email ?? "");
    if($email == ""){
        throw new Exception('User does not exist for specified username');
    }
    return $decodedToken->data ?? [];
}