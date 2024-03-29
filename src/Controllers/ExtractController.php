<?php

namespace Rinha\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;
use Rinha\Entities\Conta;
use Rinha\Entities\ExtratoDTO;
use Rinha\Entities\SaldoDTO;
use Rinha\Services\ContaService;
use Rinha\Services\TransacaoService;

class ExtractController
{
    private $contaService;
    private $transacaoService;

    public function __construct(ContaService $contaService, TransacaoService $transacaoService)
    {
        $this->contaService = $contaService;
        $this->transacaoService = $transacaoService;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');
        return $this->getContaByClienteId($id);
    }

    private function getContaByClienteId(int $id): PromiseInterface
    {
        return $this->contaService->getContaByClienteId($id)->then(
            function (?Conta $conta) {

                if ($conta === null) {
                    return Response::plaintext(
                        "Cliente não encontrado\n"
                    )->withStatus(Response::STATUS_NOT_FOUND);
                }

                $saldo = SaldoDTO::fromConta($conta);
                return $this->getDezUltimasTransacoes($conta->id, $saldo);
            }
        );
    }

    private function getDezUltimasTransacoes(int $contaId, SaldoDTO $saldo): PromiseInterface
    {
        return $this->transacaoService->getDezUltimasTransacoes($contaId)->then(
            function (?array $transacoes) use ($saldo) {
                $extrato = new ExtratoDTO($saldo, $transacoes);
                return Response::json(
                    $extrato
                );
            }
        );
    }
}
