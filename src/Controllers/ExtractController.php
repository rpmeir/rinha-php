<?php

namespace Rinha\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class ExtractController
{
    public function __invoke(ServerRequestInterface $request)
    {
        $response = ['extract' => $request->getAttribute('id')];
        return Response::json($response)->withStatus(Response::STATUS_OK);
    }
}
