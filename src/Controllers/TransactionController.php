<?php

namespace Rinha\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class TransactionController
{
    public function __invoke(ServerRequestInterface $request)
    {
        $data = json_decode((string) $request->getBody());
        $valor = $data->valor ?? '';
        $tipo = $data->tipo ?? '';
        $descricao = $data->descricao ?? '';

        $allNotEmpty = array_reduce([$valor, $tipo, $descricao], function($acc, $value) {
            return $acc && !empty($value);
        }, true);

        if(!$allNotEmpty){
            return Response::json(['error' => 'missing data'])->withStatus(Response::STATUS_UNPROCESSABLE_ENTITY);
        }

        $response = ['transaction' => $request->getAttribute('id')];
        return Response::json($response)->withStatus(Response::STATUS_OK);
    }
}
