<?php

namespace Rinha\Database\Interfaces;
use React\Promise\PromiseInterface;
use Rinha\Entities\TransacaoDTO;

interface IDatabaseStrategy
{
    public function findByClienteId(int $id): PromiseInterface;
    public function addTransaction(int $conta_id, TransacaoDTO $transacaoDTO): PromiseInterface;
    public function lastTenTransactions(int $contaId): PromiseInterface;
}
