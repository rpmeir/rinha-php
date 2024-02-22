<?php

namespace Rinha\Services;

use Rinha\Entities\Transacao;
use React\Promise\PromiseInterface;
use Rinha\Entities\Conta;
use Rinha\Entities\TransacaoDTO;
use Rinha\Repositories\TransacaoRepository;

class TransacaoService
{
    private $repository;

    public function __construct(TransacaoRepository $repository)
    {
        $this->repository = $repository;
    }

    /** @return PromiseInterface<?Transacao> **/
    public function create(Conta $conta, TransacaoDTO $transacaoDTO): PromiseInterface
    {
        // $saldoPrevisto = $conta->getSaldo() + $transacaoDTO->valor;
        // if saldo previsto ficar inconsistente com limite, rejeitar transação

        return $this->repository->add($conta->id, $transacaoDTO)->then(
            function (?Transacao $transacao) { return $transacao; }
        );
    }
}
