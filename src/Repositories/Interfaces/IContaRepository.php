<?php

namespace Rinha\Repositories\Interfaces;
use React\Promise\PromiseInterface;

interface IContaRepository
{
    public function findByClienteId(int $clienteId): PromiseInterface;
}
