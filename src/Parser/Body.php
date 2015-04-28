<?php
namespace App\Parser;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Problem\ApiProblem;

class Body
{
    public function extract(Request $request, Response $response)
    {
        $body = json_decode($request->getContent(), true);

        if (!$body) {
            return new ApiProblem(406, "Invalid JSON structure");
        }

        $request->request->replace(is_array($body) ? $body : array());
    }
}
