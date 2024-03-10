<?php

namespace Rinha\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\EventLoop\Loop;
use React\Promise\Promise;
use React\Promise\PromiseInterface;
use Rinha\Entities\ConfirmacaoTransacao;
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
            return new Promise(function ($resolve) {
                Loop::addTimer(0.01, function () use ($resolve) {
                    $jsonError = json_last_error_msg();
                    $resolve(Response::plaintext("JSON Error: $jsonError\n")
                        ->withStatus(Response::STATUS_UNPROCESSABLE_ENTITY)
                    );
                });
            });
        }

        $id = $request->getAttribute('id');
        return $this->getContaByClienteId($id, $data);
    }

    /** @return PromiseInterface<ResponseInterface> **/
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

    /** @return PromiseInterface<ResponseInterface> **/
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
                $novoSaldo = ($saldoAtual + $valorTransacao);

                return $this->updateSaldo($conta, $novoSaldo);
            }
        );
    }

    /** @return PromiseInterface<ResponseInterface> **/
    private function updateSaldo(Conta $conta, int $valor): PromiseInterface
    {
        return $this->contaService->updateSaldo($conta, $valor)->then(
            function (?ConfirmacaoTransacao $confirmacaoTransacao) {
                if ($confirmacaoTransacao === null) {
                    return Response::plaintext(
                        "Erro ao atualizar o saldo\n"
                    )->withStatus(Response::STATUS_UNPROCESSABLE_ENTITY);
                }
                return Response::json($confirmacaoTransacao)->withStatus(Response::STATUS_OK);
            }
        );
    }
}
