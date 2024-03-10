<?php

namespace Rinha\Repositories\Interfaces;

use React\Promise\PromiseInterface;
use Rinha\Entities\Conta;

interface IContaRepository
{
    public function findByClienteId(int $clienteId): PromiseInterface;
    public function updateSaldo(Conta $conta, int $valor): PromiseInterface;
}
