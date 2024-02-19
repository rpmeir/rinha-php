<?php

namespace Rinha\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use Rinha\Repositories\ClienteRepository;

class ClienteController
{
    private $repository;

    public function __construct(ClienteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $cliente = $this->repository->find($id);

        if ($cliente === null) {
            return Response::plaintext(
                "Cliente não encontrado\n"
            )->withStatus(Response::STATUS_NOT_FOUND);
        }

        return Response::json(
            $cliente
        );
    }
}
