<?php

namespace Rinha\Services\Interfaces;

use React\Promise\PromiseInterface;
use Rinha\Entities\Conta;

interface IContaService
{
    public function getContaByClienteId(int $clienteId): PromiseInterface;
    public function updateSaldo(Conta $conta, int $valor): PromiseInterface;
}
