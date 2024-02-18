<?php

namespace Rinha\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class ExtractController
{
    public function __invoke(ServerRequestInterface $request)
    {
        $data = json_decode((string) $request->getBody());
        $name = $data->name ?? 'anonymous';
        $response = ['extract' => $request->getAttribute('id')];
        return Response::json($response)->withStatus(Response::STATUS_OK);
    }
}
