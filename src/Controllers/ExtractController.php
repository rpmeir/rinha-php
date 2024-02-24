<?php

namespace Rinha\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use Rinha\Entities\Conta;
use Rinha\Services\ContaService;

class ExtractController
{
    private $contaService;

    public function __construct(ContaService $contaService)
    {
        $this->contaService = $contaService;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');
        return $this->contaService->getContaByClienteId($id)->then(
            function (?Conta $conta) {

                if ($conta === null) {
                    return Response::plaintext(
                        "Cliente não encontrado\n"
                    )->withStatus(Response::STATUS_NOT_FOUND);
                }

                return Response::json(
                    $conta
                );
            }
        );
    }
}
