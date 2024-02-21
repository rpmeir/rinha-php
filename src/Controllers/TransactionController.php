<?php

namespace Rinha\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;
use Rinha\Entities\Conta;
use Rinha\Services\ContaService;

class TransactionController
{
    private $contaService;

    public function __construct(ContaService $contaService)
    {
        $this->contaService = $contaService;
    }

    /** @return PromiseInterface<ResponseInterface> **/
    public function __invoke(ServerRequestInterface $request): PromiseInterface
    {
        $data = json_decode((string) $request->getBody());

        $id = $request->getAttribute('id');
        return $this->contaService->getContaByClienteId($id)->then(function (?Conta $conta) use ($data) {

            if ($conta === null) {
                return Response::plaintext(
                    "Conta não encontrada\n"
                )->withStatus(Response::STATUS_NOT_FOUND);
            }

            $valor = $data->valor ?? '';
            $tipo = $data->tipo ?? '';
            $descricao = $data->descricao ?? '';

            return Response::json(
                $conta
            );
        });
    }
}
