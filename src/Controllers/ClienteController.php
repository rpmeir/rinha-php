<?php

namespace Rinha\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;
use Rinha\Entities\Cliente;
use Rinha\Services\ClienteService;

class ClienteController
{
    private $service;

    public function __construct(ClienteService $service)
    {
        $this->service = $service;
    }

    /** @return PromiseInterface<ResponseInterface> **/
    public function __invoke(ServerRequestInterface $request): PromiseInterface
    {
        $id = $request->getAttribute('id');
        return $this->service->getCliente($id)->then(
            function (?Cliente $cliente) {

                if ($cliente === null) {
                    return Response::plaintext(
                        "Cliente não encontrado\n"
                    )->withStatus(Response::STATUS_NOT_FOUND);
                }

                return Response::json(
                    $cliente
                );
            }
        );
    }
}
