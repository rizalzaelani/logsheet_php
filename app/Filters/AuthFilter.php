<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
		if(!$session->has('userId')){
            $req = \Config\Services::request();
            if ($req->isAJAX()) {
                $response = \Config\Services::response();
                $response->setStatusCode(401);
                $response->send();
                die();
            }
            else 
            {
                $returnUrl = urlencode($_SERVER['REQUEST_URI']);
                return redirect()->to('?ReturnUrl='.$returnUrl);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}