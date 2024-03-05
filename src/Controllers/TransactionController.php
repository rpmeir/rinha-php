<?php

namespace Rinha\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use Rinha\Entities\Conta;
use Rinha\Entities\Transacao;
use Rinha\Entities\TransacaoDTO;
use Rinha\Services\ContaService;
use Rinha\Services\TransacaoService;

class TransactionController
{
    private $contaService;
    private $transacaoService;

    public function __construct(ContaService $contaService, TransacaoService $transacaoService)
    {
        $this->contaService = $contaService;
        $this->transacaoService = $transacaoService;
    }

    /** @return PromiseInterface<ResponseInterface> **/
    public function __invoke(ServerRequestInterface $request): PromiseInterface
    {
        $data = json_decode((string) $request->getBody());

        if($data === null) {
            $deferred = new Deferred();
            $promise = $deferred->promise();
            $jsonError = json_last_error_msg();
            $promise = $promise->then(function () use ($jsonError) {
                return Response::plaintext(
                    "JSON Error: $jsonError\n"
                )->withStatus(Response::STATUS_UNPROCESSABLE_ENTITY);
            });
            $deferred->resolve(null);
            return $promise;
        }

        $id = $request->getAttribute('id');
        return $this->getContaByClienteId($id, $data);
    }

    private function getContaByClienteId(int $id, object $data): PromiseInterface
    {
        return $this->contaService->getContaByClienteId($id)->then(
            function (?Conta $conta) use ($data) {

                if ($conta === null) {
                    return Response::plaintext(
                        "Cliente não encontrado\n"
                    )->withStatus(Response::STATUS_NOT_FOUND);
                }

                $transacaoDTO = $this->transacaoService->transacaoValida($conta, $data);
                if (!$transacaoDTO instanceof TransacaoDTO) {
                    return Response::plaintext(
                        "$transacaoDTO\n"
                    )->withStatus(Response::STATUS_UNPROCESSABLE_ENTITY);
                }

                return $this->criarTransacao($conta, $transacaoDTO);
            }
        );
    }

    private function criarTransacao(Conta $conta, TransacaoDTO $transacaoDTO): PromiseInterface
    {
        return $this->transacaoService->create($conta, $transacaoDTO)->then(
            function (?Transacao $transacao) use ($conta) {
                if ($transacao === null) {
                    return Response::plaintext(
                        "Erro ao salvar a transacao\n"
                    )->withStatus(Response::STATUS_UNPROCESSABLE_ENTITY);
                }

                $saldoAtual = $conta->getSaldo();
                $valorTransacao = $transacao->valor * ($transacao->tipo === 'd' ? -1 : 1);
                $conta->setSaldo($saldoAtual + $valorTransacao);

                return Response::json(
                    ['limite' => $conta->limite, 'saldo' => $conta->getSaldo()]
                );
            }
        );
    }
}
