<?php

namespace Rinha\Repositories\Interfaces;

use React\Promise\PromiseInterface;
use Rinha\Entities\TransacaoDTO;

interface ITransacaoRepository
{
    public function add(int $conta_id, TransacaoDTO $transacaoDTO): PromiseInterface;
    public function lastTenTransactions(int $contaId): PromiseInterface;
}
