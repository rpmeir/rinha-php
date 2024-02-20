<?php

namespace Rinha\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use Rinha\Repositories\ClienteRepository;
use React\Promise\PromiseInterface;
use Rinha\Entities\Cliente;

class ClienteController
{
    private $repository;

    public function __construct(ClienteRepository $repository)
    {
        $this->repository = $repository;
    }

    /** @return PromiseInterface<ResponseInterface> **/
    public function __invoke(ServerRequestInterface $request): PromiseInterface
    {
        $id = $request->getAttribute('id');
        return $this->repository->find($id)->then(function (?Cliente $cliente) {

            if ($cliente === null) {
                return Response::plaintext(
                    "Cliente não encontrado\n"
                )->withStatus(Response::STATUS_NOT_FOUND);
            }

            return Response::json(
                $cliente
            );
        });
    }
}
