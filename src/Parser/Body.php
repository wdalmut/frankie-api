<?php
namespace App\Parser;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Problem\ApiProblem;

class Body
{
    public function extract(Request $request, Response $response)
    {
        if (!in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            return;
        }

        $body = json_decode($request->getContent(), true);

        if (!$body) {
            return new ApiProblem(400, "Invalid JSON structure");
        }

        $request->request->replace(is_array($body) ? $body : array());
    }
}
