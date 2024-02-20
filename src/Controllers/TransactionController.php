<?php

namespace Rinha\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use Rinha\Repositories\ClienteRepository;
use React\Promise\PromiseInterface;

class TransactionController
{
    private $clienteRepository;

    public function __construct(ClienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    /** @return PromiseInterface<ResponseInterface> **/
    public function __invoke(ServerRequestInterface $request): PromiseInterface
    {
        $data = json_decode((string) $request->getBody());
        $valor = $data->valor ?? '';
        $tipo = $data->tipo ?? '';
        $descricao = $data->descricao ?? '';


        $id = $request->getAttribute('id');
        return $this->repository->add($id)->then(function (?Cliente $cliente) {

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
