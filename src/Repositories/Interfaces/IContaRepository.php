<?php

namespace Rinha\Repositories\Interfaces;

interface IContaRepository
{
    public function findByClienteId(int $clienteId): PromiseInterface;
}
