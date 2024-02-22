<?php

namespace Rinha\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
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

        $id = $request->getAttribute('id');
        return $this->contaService->getContaByClienteId($id)->then(
            function (?Conta $conta) use ($data) {

                if ($conta === null) {
                    return Response::plaintext(
                        "Cliente não encontrado\n"
                    )->withStatus(Response::STATUS_NOT_FOUND);
                }

                $transacaoDTO = TransacaoDTO::create($data->valor, $data->tipo, $data->descricao);
                if ($transacaoDTO === null) {
                    return Response::plaintext(
                        "Entidade incorreta\n"
                    )->withStatus(Response::STATUS_UNPROCESSABLE_ENTITY);
                }

                $transacao = $this->transacaoService->create($conta, $transacaoDTO)->then(
                    function (?Transacao $transacao) { return $transacao; }
                );

                return Response::json(
                    $transacao
                );
            }
        );
    }
}
