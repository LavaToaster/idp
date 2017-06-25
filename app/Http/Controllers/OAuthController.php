<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OAuthController extends Controller
{
    public function issueAccessToken(ServerRequestInterface $request, ResponseInterface $response, AuthorizationServer $server)
    {
        try {
            // Try to respond to the access token request
            return $server->respondToAccessTokenRequest($request, $response);
        } catch (OAuthServerException $exception) {
            // All instances of OAuthServerException can be converted to a PSR-7 response
            return $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            // Catch unexpected exceptions
            $body = $response->getBody();
            $body->write($exception->getMessage());

            return $response->withStatus(500)->withBody($body);
        }
    }
}