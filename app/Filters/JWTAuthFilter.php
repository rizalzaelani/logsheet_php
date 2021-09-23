<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;
use Firebase\JWT\JWT;
use CodeIgniter\API\ResponseTrait;
use DateTimeImmutable;
use Exception;

class JWTAuthFilter implements FilterInterface
{
	use ResponseTrait;

	public function before(RequestInterface $request, $arguments = null)
	{
		$key        = getenv('JWT_SECRET_KEY');
		$authHeader = $request->getServer('HTTP_AUTHORIZATION');
		$arr        = explode(' ', $authHeader);
		$token      = (count($arr) > 1 ? $arr[1] : "");
		$now 		= new DateTimeImmutable();

		try {
			$data = JWT::decode($token, $key, ['HS256']);

			if ($data->iss !== getenv("DOMAIN_NAME") || $data->nbf > $now->getTimestamp() || $data->exp < $now->getTimestamp()) {
				return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
			}

			helper('JWTAuth');
            $encodedToken = getJWTFromRequest($authHeader);
            validateJWTFromRequest($encodedToken);
            return $request;
		} catch (Exception $e) {
			return Services::response()->setJSON(['error' => $e->getMessage()])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
		}
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		// Do something here
	}
}
